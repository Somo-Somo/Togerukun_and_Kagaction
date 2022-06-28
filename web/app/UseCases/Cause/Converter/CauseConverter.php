<?php

namespace App\UseCases\Cause\Converter;

class CauseConverter
{
    public function invoke($fetchCauses)
    {
        $causes = [];

        foreach ($fetchCauses as $key => $value) {
            $user = $value->getNodes()[0]->getProperties()->toArray();
            $cause = $value->getNodes()[1]->getProperties()->toArray();

            $cause['user_uuid'] = $user['uuid'];
            $cause['user_name'] = $user['name'];

            $causes[] = $cause;
        }

        return $causes;
    }
}
