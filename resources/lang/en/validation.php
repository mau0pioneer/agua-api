<?php

return [
    'accepted' => 'El :attribute debe ser aceptado.',
    'accepted_if' => 'El :attribute debe ser aceptado cuando :other sea :value.',
    'active_url' => 'El :attribute debe ser una URL válida.',
    'after' => 'El :attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => 'El :attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => 'El :attribute solo debe contener letras.',
    'alpha_dash' => 'El :attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El :attribute solo debe contener letras y números.',
    'array' => 'El :attribute debe ser un conjunto.',
    'ascii' => 'El :attribute solo debe contener caracteres alfanuméricos y símbolos de un solo byte.',
    'attached' => 'Este :attribute ya se adjuntó.',
    'before' => 'El :attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'array' => 'El :attribute tiene que tener entre :min - :max elementos.',
        'file' => 'El :attribute debe pesar entre :min - :max kilobytes.',
        'numeric' => 'El :attribute tiene que estar entre :min - :max.',
        'string' => 'El :attribute tiene que tener entre :min - :max caracteres.',
    ],
    'boolean' => 'El :attribute debe tener un valor verdadero o falso.',
    'can' => 'El :attribute contiene un valor no autorizado.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => 'El :attribute debe ser una fecha válida.',
    'date_equals' => 'El :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El :attribute debe coincidir con el formato :format.',
    'decimal' => 'El :attribute debe tener :decimal cifras decimales.',
    'declined' => 'El :attribute debe ser rechazado.',
    'declined_if' => 'El :attribute debe ser rechazado cuando :other sea :value.',
    'different' => 'El :attribute y :other deben ser diferentes.',
    'digits' => 'El :attribute debe tener :digits dígitos.',
    'digits_between' => 'El :attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'El :attribute tiene dimensiones de imagen no válidas.',
    'distinct' => 'El :attribute contiene un valor duplicado.',
    'doesnt_end_with' => 'El :attribute no debe finalizar con uno de los siguientes: :values.',
    'doesnt_start_with' => 'El :attribute no debe comenzar con uno de los siguientes: :values.',
    'email' => 'El :attribute no es un correo válido.',
    'ends_with' => 'El :attribute debe finalizar con uno de los siguientes valores: :values',
    'enum' => 'El :attribute seleccionado es inválido.',
    'exists' => 'El :attribute seleccionado es inválido.',
    'extensions' => 'El :attribute debe tener una de las siguientes extensiones: :values.',
    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'file' => 'El :attribute debe ser un archivo.',
    'filled' => 'El :attribute es obligatorio.',
    'gt' => [
        'array' => 'El :attribute debe tener más de :value elementos.',
        'file' => 'El :attribute debe tener más de :value kilobytes.',
        'numeric' => 'El :attribute debe ser mayor que :value.',
        'string' => 'El :attribute debe tener más de :value caracteres.',
    ],
    'gte' => [
        'array' => 'El :attribute debe tener como mínimo :value elementos.',
        'file' => 'El :attribute debe tener como mínimo :value kilobytes.',
        'numeric' => 'El :attribute debe ser como mínimo :value.',
        'string' => 'El :attribute debe tener como mínimo :value caracteres.',
    ],
    'hex_color' => 'El :attribute debe tener un color hexadecimal válido.',
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El :attribute seleccionado no es válido.',
    'in_array' => 'El :attribute debe existir en :other.',
    'integer' => 'El :attribute debe ser un número entero.',
    'ip' => 'El :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El :attribute debe ser una cadena JSON válida.',
    'lowercase' => 'El :attribute debe estar en minúscula.',
    'lt' => [
        'array' => 'El :attribute debe tener menos de :value elementos.',
        'file' => 'El :attribute debe tener menos de :value kilobytes.',
        'numeric' => 'El :attribute debe ser menor que :value.',
        'string' => 'El :attribute debe tener menos de :value caracteres.',
    ],
    'lte' => [
        'array' => 'El :attribute debe tener como máximo :value elementos.',
        'file' => 'El :attribute debe tener como máximo :value kilobytes.',
        'numeric' => 'El :attribute debe ser como máximo :value.',
        'string' => 'El :attribute debe tener como máximo :value caracteres.',
    ],
    'mac_address' => 'El :attribute debe ser una dirección MAC válida.',
    'max' => [
        'array' => 'El :attribute no debe tener más de :max elementos.',
        'file' => 'El :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => 'El :attribute no debe ser mayor que :max.',
        'string' => 'El :attribute no debe ser mayor que :max caracteres.',
    ],
    'mimes' => 'El :attribute debe ser un archivo con formato: :values.',
    'mimetypes' => 'El :attribute debe ser un archivo con formato: :values.',
    'min' => [
        'array' => 'El :attribute debe tener al menos :min elementos.',
        'file' => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'string' => 'El :attribute debe contener al menos :min caracteres.',
    ],
    'min_digits' => 'El :attribute debe tener al menos :min dígitos.',
    'missing' => 'El :attribute no debe estar presente.',
    'missing_if' => 'El :attribute no debe estar presente cuando :other sea :value.',
    'missing_unless' => 'El :attribute no debe estar presente a menos que :other sea :value.',
    'missing_with' => 'El :attribute no debe estar presente si alguno de los campos :values está presente.',
    'missing_with_all' => 'El :attribute no debe estar presente cuando los campos :values estén presentes.',
    'multiple_of' => 'El :attribute debe ser múltiplo de :value',
    'next' => 'Siguiente &raquo;',
    'not_in' => 'El :attribute seleccionado no es válido.',
    'not_regex' => 'El formato del :attribute no es válido.',
    'numeric' => 'El :attribute debe ser numérico.',
    'password' => 'La :attribute es incorrecta.',
    'password.letters' => 'La :attribute debe contener al menos una letra.',
    'password.mixed' => 'La :attribute debe contener al menos una letra mayúscula y una minúscula.',
    'password.numbers' => 'La :attribute debe contener al menos un número.',
    'password.symbols' => 'La :attribute debe contener al menos un símbolo.',
    'password.uncompromised' => 'La :attribute proporcionada se ha visto comprometida en una filtración de datos (data leak). Elija una :attribute diferente.',
    'present' => 'El :attribute debe estar presente.',
    'present_if' => 'El :attribute debe estar presente cuando :other es :value.',
    'present_unless' => 'El :attribute debe estar presente a menos que :other sea :value.',
    'present_with' => 'El :attribute debe estar presente cuando :values esté presente.',
    'present_with_all' => 'El :attribute debe estar presente cuando :values estén presentes.',
    'previous' => '&laquo; Anterior',
    'prohibited' => 'El :attribute está prohibido.',
    'prohibited_if' => 'El :attribute está prohibido cuando :other es :value.',
    'prohibited_unless' => 'El :attribute está prohibido a menos que :other sea :values.',
    'prohibits' => 'El :attribute prohibe que :other esté presente.',
    'regex' => 'El formato del :attribute no es válido.',
    'relatable' => 'Este :attribute no se puede asociar con este recurso',
    'required' => 'El :attribute es obligatorio.',
    'required_array_keys' => 'El :attribute debe contener entradas para: :values.',
    'required_if' => 'El :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El :attribute es obligatorio si :other es aceptado.',
    'required_unless' => 'El :attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => 'El :attribute es obligatorio cuando :values está presente.',
    'required_with_all' => 'El :attribute es obligatorio cuando :values están presentes.',
    'required_without' => 'El :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El :attribute es obligatorio cuando ninguno de :values está presente.',
    'reset' => 'Su contraseña ha sido restablecida.',
    'same' => 'Los campos :attribute y :other deben coincidir.',
    'sent' => 'Le hemos enviado por correo electrónico el enlace para restablecer su contraseña.',
    'size' => [
        'array' => 'El :attribute debe contener :size elementos.',
        'file' => 'El tamaño de :attribute debe ser :size kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'string' => 'El :attribute debe contener :size caracteres.',
    ],
    'starts_with' => 'El :attribute debe comenzar con uno de los siguientes valores: :values',
    'string' => 'El :attribute debe ser una cadena de caracteres.',
    'throttle' => 'Demasiados intentos de acceso. Por favor intente nuevamente en :seconds segundos.',
    'throttled' => 'Por favor espere antes de intentar de nuevo.',
    'timezone' => 'El :attribute debe ser una zona horaria válida.',
    'token' => 'El token de restablecimiento de contraseña es inválido.',
    'ulid' => 'El :attribute debe ser un ULID válido.',
    'unique' => 'El :attribute ya ha sido registrado.',
    'uploaded' => 'Subir :attribute ha fallado.',
    'uppercase' => 'El :attribute debe estar en mayúscula.',
    'url' => 'El :attribute debe ser una URL válida.',
    'user' => 'No encontramos ningún usuario con ese correo electrónico.',
    'uuid' => 'El :attribute debe ser un UUID válido.',
    'attributes' => [
        // Puedes agregar más atributos personalizados aquí si es necesario.
        'email' => 'correo electrónico',
        'password' => 'contraseña',
    ],
];