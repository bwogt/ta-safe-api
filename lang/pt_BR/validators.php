<?php

return [
    'auth' => [
        'invalid_credentials' => 'Credenciais inválidas.',
    ],
    'device' => [
        'user_must_be_owner' => 'Somente o proprietário do dispositivo pode realizar esta ação.',
        'invalid_device_state' => 'Este dispositivo está em um estado inválido para realizar esta operação.',
        'status' => [
            'in_analysis' => 'O registro de validação do dispositivo precisa estar com status em análise.',
        ],
        'transfer' => [
            'same_user' => 'Não é possível transferir um dispositivo para si mesmo.',
            'pending' => 'O dispositivo possui uma transferência pendente.',
            'not_pending' => 'A transferência do dispositivo precisa estar pendente.',
            'not_source_user' => 'Somente o usuário de origem da transferência pode realizar esta ação.',
            'not_target_user' => 'Somente o usuário de destino da transferência pode realizar esta ação.',
        ],
    ],
];
