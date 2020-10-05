<?php

return array(

	'secret'  => getenv('NOCAPTCHA_SECRET') ?: 'victoriasecret',
	'sitekey' => getenv('NOCAPTCHA_SITEKEY') ?: 'getit',

	'lang'    => app()->getLocale(),

);
