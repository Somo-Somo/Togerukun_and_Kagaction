<?php

namespace App\UseCases\Cause\Converter;

class CauseConverter
{
    /**
     * Cypherで引っ張ってきたものをuserとcauseの連想配列の形変換にする
     *
     * @param array $fetch_causes
     * @return array $causes
     */
    public function invoke(array $fetch_causes)
    {
        $causes = [];

        foreach ($fetch_causes as $key => $value) {
            $user = $value->getNodes()[0]->getProperties()->toArray();
            $cause = $value->getNodes()[1]->getProperties()->toArray();

            $cause['user_uuid'] = $user['uuid'];
            $cause['user_name'] = $user['name'];

            $causes[] = $cause;
        }

        return $causes;
    }
}
