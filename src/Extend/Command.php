<?php

namespace Haunt\Framework\Extend;

use Illuminate\Validation\ValidationException;
use LumiteStudios\Common\Extend\Command as BaseCommand;

class Command extends BaseCommand
{
    public function validate(\Closure $validate, ?\Closure $loop = null): mixed
    {
        try {
            $state = $validate();

            return $state;
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $this->error($error[0]);
            }

            return $this->validate($loop ?? $validate);
        }
    }
}
