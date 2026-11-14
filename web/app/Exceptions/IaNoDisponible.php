<?php

namespace App\Exceptions;

use RuntimeException;

// Se lanza cuando Groq no puede responder
class IaNoDisponible extends RuntimeException {}
