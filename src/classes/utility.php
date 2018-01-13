<?php

    class utility
    {
        public static function checkChatCode($code)
		{
			if (preg_match('/^[A-Za-z0-9-]{10}$/', $code)) {
				return true;
			}

			return false;
        }

        public static function getRandomChatCode()
        {
            return bin2hex(random_bytes(5));
        }

        public static function getRandomNickname($maxChars = null)
		{
			$cuteAnimals = [
				'Feneco', 'Társio-das-Filipinas', 'Panda', 'Foca da Groelândia',
				'Lontra-marinha', 'Colibri-abelha-cubano', 'Alpaca', 'Golfinho',
				'Corça', 'Chinchila', 'Peixe-palhaço', 'Baleia-branca',
				'Panda-vermelho', 'Pinguim', 'Coala', 'Suricate', 'Camaleão',
				'Lóris-lento-pigmeu', 'Hipopótamo-pigmeu', 'Bicho-preguiça'
            ];

            if (is_int($maxChars) && $maxChars >= 5) {
                $attempts = 0;
                while (true) {
                    $nickname = $cuteAnimals[array_rand($cuteAnimals)];
                    if (strlen($nickname) <= $maxChars) {
                        break;
                    } else {
                        $attempts++;
                        if ($attempts > 100) {
                            throw new Exception('O limite de tentativas para achar um nickname estourou');
                        }
                    }
                }
            }

			return $nickname ?? $cuteAnimals[array_rand($cuteAnimals)];
		}
    }
