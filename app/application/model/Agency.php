<?php

namespace Adah;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Agencies extends Model
{
    public function validation()
    {
        $this->validate(
            new InclusionIn(
                [
                    "field"  => "status",
                    "domain" => [
                        "open",
                        "closed"
                    ]
                ]
            )
        );

        $this->validate(
            new InclusionIn(
                [
                    "field"  => "closedtype",
                    "domain" => [
                        "merged",
                        "bought",
                        "shutdown"
                    ]
                ]
            )
        );

        // Robot name must be unique
        $this->validate(
            new Uniqueness(
                [
                    "field"   => "name",
                    "message" => "The robot name must be unique",
                ]
            )
        );

        // Year cannot be less than zero
        if ($this->year < 0) {
            $this->appendMessage(
                new Message("The year cannot be less than zero")
            );
        }

        // Check if any messages have been produced
        if ($this->validationHasFailed() === true) {
            return false;
        }
    }
}