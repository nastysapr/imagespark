<?php
namespace App\Service;

use App\Models\User;

class ContentGenerator
{
    public string $sourceText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    public array $dictionary;

    public function __construct(string $entity, int $count)
    {
        $this->dictionary = require ('__DIR__'. '/../config/gen_config.php');

        $users = (new User)->all();
        foreach ($users as $user){
            $authors[] = $user->full_name;
        }

        $class = ucfirst($entity);

        for ($i = 0; $i < $count; $i++) {
            $newEntity = new $class();
            $newEntity->title =  $this->dictionary['title'][array_rand($this->dictionary['title'])];
            $newEntity->author = $authors[array_rand($authors)];
            $newEntity->text = $this->dictionary['text'][array_rand($this->dictionary['text'])];
            $newEntity->date = date("Y-m-d");
            $newEntity->id = $newEntity->calcId();
            $newEntity->save();
        }
    }

//    public function generateText(): string
//    {
//        $generatedText = $this->randome(' ');
//        return $this->randome('. ', $generatedText);
//    }
//
//    public function generateHeader(): string
//    {
//        $generatedText = $this->randome(' ');
//        $header = explode('. ', $generatedText);
//        return ucfirst($header[array_key_first($header)]);
//    }
//
//    public function getAuthor(array $authors): string
//    {
//        return $authors[array_rand($authors)]['full_name'];
//    }
//
//    public function randome(string $separator, string $generatedText = ''): string
//    {
//        if ($separator == ' ') {
//            $result = explode($separator, strtolower($this->sourceText));
//            shuffle($result);
//        } else {
//            $sourceWords = explode($separator, $generatedText);
//            $result = array_map(function ($str) {
//                return ucfirst($str);
//            }, $sourceWords);
//        }
//        return implode($separator, $result);
//    }
}
