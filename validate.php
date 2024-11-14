<?php
class Validate
{
    public function vName($name)
    {
        if (empty($name)) {
            return "Name Cannot Be Empty";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            return "Invalid Name Format. Only Alphabets and Spaces are Allowed.";
        } else {
            $len = strlen($name);
            if ($len < 3) {
                return "Name Must be at Least 3 Characters Long.";
            }
            for ($i = 0; $i < $len - 1; $i++) {
                if (ord($name[$i]) == 32 && ord($name[$i + 1]) == 32) {
                    return "Spaces Are Not Allowed Next to Each Other.";
                }
            }
            return "";   //return empty string if all conditions met
        }
    }
    public function vEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Please provide a valid email address";
        } else {
            list($user, $domain) = explode("@", $email);
            if (strlen($user) < 2 || strlen($user) > 64) {
                return "Username should contain between 1 and 64 characters";
            }
            if (
                substr_count($domain, "..") || !in_array(strtolower(substr($domain, -3)), [
                    "com",
                    "net",
                    "org",
                    "edu"
                ])
            ) {
                return 'The domain name is invalid';
            }
        }
        return '';    //return empty string if all conditions met
    }
    public function vNumber($num)
    {
        if (strlen($num) != 10) {
            return "The number must have 10 digits";
        } else {
            return "";     //return empty string if all conditions met
        }
    }
    //Validate Password
    public function vPassword($pass)
    {
        // Check for common passwords or dictionary words
        $common_passwords = array("password", "123456", "12345678", "admin", "qwerty", "123456789", "football", "1234", "12345", "baseball", "welcome", "1234567", "support", "dream", "111111", "classic", "computer", "solo", "princess", "login", "qazwsx", "lol", "123123", "abc123", "admin123", "iloveyou", "123321", "monkey", "666666", "password1", "trustno1", "dragon", "987654", "michael", "shadow", "master", "jennifer", "121212", "hello", "freedom", "whatever", "jesus", "naruto", "asdf", "hottie", "superman", "michelle", "basketball", "matrix", "letmein", "iloveyou1", "football1", "654321", "angel", "bigdog", "babygirl", "spiderman", "jordan", "harley", "hockey", "george", "phoenix", "ranger", "sunshine", "michael2", "robert", "joseph", "thomas", "charlie", "andrew", "jessica", "daniel", "emily", "melissa", "nicholas", "jennifer2", "jason", "amanda", "brandon", "matthew", "joshua", "ashley", "donald", "linda", "kenneth", "katherine", "steve", "paula", "mark", "patricia", "kevin", "gloria", "scott", "sandra", "james", "susan", "edward", "maria", "ronald", "john", "robert2", "michelle2", "laura", "jose", "lisa", "kim", "jennifer3", "william", "jessica2", "david", "helen", "timothy", "amy", "howard", "diana", "kevin2", "stephen", "andrea", "terry", "carol", "russell", "joseph2", "carla", "thomas2", "louis", "anne", "joseph3", "edward2", "jeffrey", "karen", "paul", "janet", "larry", "cynthia", "eric", "nancy", "jason2", "mary", "john2", "james2", "martha", "patrick", "donna", "daniel2", "joseph4", "lucas", "stephanie", "emily2", "matthew2", "jennifer4", "mark2", "donald2", "sara", "melissa2", "gregory", "catherine", "joshua2", "rebecca", "josh", "sharon", "joseph5", "joseph6", "joseph7", "joseph8", "joseph9", "passw0rd", "kathmandu", "mount Everest", "annapurna", "mustang", "lumbini", "biratnagar", "pokhara", "nepal", "dhaka", "bangladesh", "bhutan", "sagarmatha", "gurkha", "daura suruwal", "momo", "dal bhat", "dhangadi", "itahari", "birgunj", "butwal", "bhairahawa", "nepali", "hindi", "english", "maithili", "bhojpuri", "tibetan", "newari", "tamang", "gurung", "magar", "tharu", "rautela", "chhetri", "brahmin", "kami", "damai", "sarki", "limbu", "rai", "sunuwar", "yadav", "thakuri", "jha", "khanal", "mishra", "shrestha", "prasai", "regmi", "karki", "khatri", "pandey", "gautam", "bastola", "lamichhane", "bhandari", "thapa", "dahal", "amatya", "sharma", "neupane", "devkota", "karki2", "karki3", "karki4", "karki5", "karki6", "karki7", "karki8", "karki9");
        $pattern = '/^(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?=.*[A-Za-z])(?=.*[^\w\d\s]).*$/';
        if (in_array(strtolower($pass), $common_passwords)) {
            return "Common password detected, cannot be used.";
        } elseif (preg_match('/(.)\1\1/', $pass)) {
            return "Password cannot have repetitive patterns.";
        } elseif (!preg_match($pattern, $pass)) {
            if (!preg_match('/[A-Za-z]/', $pass)) {
                return "Password must include at least one letter.";
            }
            if (!preg_match('/\d/', $pass)) {
                return "Password must include at least one number.";
            }
            if (!preg_match('/[^\w\d\s]/', $pass)) {
                return "Password must include at least one special character.";
            }
            if (strlen($pass) < 8) {
                return "Password must be at least 8 characters long.";
            }
        } else {
            return "";   // Return empty string if all conditions are met
        }
    }
    public function vCPassword($pass, $cpass)
    {
        if ($pass != $cpass) {
            return "Passwords do not match.";
        } else {
            return "";
        }
    }

    public function vAddress($address)
    {
        $length = strlen($address);
        if ($length <= 0) {
            return "Address cannot be empty";
        } else if ($length > 256) {
            return "Address too long";
        } else {
            return "";   //return empty string if all conditions met
        }
    }

    public function vProduct($prod_name, $prod_price, $prod_desc, $prod_category)
    {
        if (empty($prod_name)) {
            return "❌Product name cannot be empty.";
        } elseif (strlen($prod_name) <= 3) {
            return "❌Product name must be at least 4 characters long.";
        }

        if ($prod_price === null || !is_numeric($prod_price)) {
            return "❌Invalid product price. Please enter a valid numeric value.";
        } elseif ($prod_price < 0) {
            return "❌Product price cannot be negative.";
        }

        if (empty($prod_desc)) {
            return "❌Product description cannot be empty.";
        } elseif (strlen($prod_desc) > 5000) {
            return "❌Product description exceeds the maximum allowed length of 5000 characters.";
        }

        if (empty($prod_category)) {
            return "❌Product category cannot be empty.";
        }

        return "";
    }
}