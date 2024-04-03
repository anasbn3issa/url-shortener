<?php

// src/Service/UrlShortenerService.php

namespace App\Service;

class UrlShortenerService
{
    public function generateShortCode(): string
    {
        // Generates a random 6-character string
        // Consider a more sophisticated approach for production use
        return substr(str_shuffle(md5(time())), 0, 6);
    }
}
