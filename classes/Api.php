<?php declare(strict_types = 1);

namespace Ems;

class Api
{
	const States = [
		'ERROR'            => 'ERROR',
		'IDLE'             => 'IDLE',
		'PENDING'          => 'PENDING',
		'SUCCESS'          => 'SUCCESS',
		'VALIDATION_ERROR' => 'VALIDATION_ERROR',
	];

	const DefaultResponse = [
		'body'  => null,
		'error' => null,
		'state' => self::States['PENDING'],
	];

	const UnexpectedError = 'An unexpected error occurred, please try again.';
}
