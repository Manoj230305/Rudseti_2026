<?php
session_start();
$sessionData = print_r($_SESSION, true);

// Send session data as a custom header
header("X-Session-Data: " . str_replace(["\n", "\r"], " ", $sessionData));

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}

class Certificates
{ 
    private $batchFolder; // Folder where batch images will be stored

    public function __construct() {
        // Set the base folder where batch images will be stored
        $this->batchFolder = 'certificates/' . $_SESSION['batch'] . '/';
        if (!file_exists($this->batchFolder)) {
            mkdir($this->batchFolder, 0777, true); // Create batch folder if it doesn't exist
        }
    }

    // Function to check if GD extension is available
    private function checkDependencies() {
        if (!extension_loaded('gd')) {
            header('HTTP/1.1 500 Internal Server Error');
            exit('Internal server error. Please try again later.');
        }
    }

    // Function to check if image file exists and is readable
    private function checkImageFile($imagePath) {
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            header('HTTP/1.1 404 Not Found');
            exit('Image file not found or unreadable.');
        }
    }

    // Function to load image
    private function loadImage($imagePath) {
        $image = @imagecreatefrompng($imagePath); // Use @ to suppress errors
        if (!$image) {
            $image = @imagecreatefromjpeg($imagePath);
            if (!$image) {
                $image = imagecreatefromwebp($imagePath);
                if(!$image){
                    header('HTTP/1.1 500 Internal Server Error');
                    exit('Internal server error. Unable to process image.');
                }
            }
        }
        return $image;
    }

    // Function to resize image
    private function resizeImage($image, $width, $height) {
        $resizedImage = imagescale($image, $width, $height, IMG_BILINEAR_FIXED); // Use bilinear interpolation for better quality
        if (!$resizedImage) {
            header('HTTP/1.1 500 Internal Server Error');
            exit('Internal server error. Unable to resize image.');
        }
        return $resizedImage;
    }

    // Function to add fixed position text to image
    private function addFixedText($image, $text, $fontFile, $fontColor, $fontSize, $x, $y) {
        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $fontFile, $text);
    }

    // Function to add text to image with proper alignment
    private function addText($image, $text, $fontFile, $fontColor, $fontSize, $startX, $endX, $y) {
        // Add padding to prevent text from touching the boundaries
        $padding = 15;
        $effectiveStartX = $startX + $padding;
        $effectiveEndX = $endX - $padding;
        $maxWidth = $effectiveEndX - $effectiveStartX;
    
        // Calculate text bounding box
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = $textBox[2] - $textBox[0];
    
        // Check if text width exceeds the maximum allowed width
        if ($textWidth > $maxWidth) {
            // Calculate new font size to fit the text within the range
            $newFontSize = $fontSize * ($maxWidth / $textWidth);
    
            // Recalculate text bounding box with the new font size to center it perfectly
            $newTextBox = imagettfbbox($newFontSize, 0, $fontFile, $text);
            $newTextWidth = $newTextBox[2] - $newTextBox[0];
            
            // Calculate X-coordinate to center the scaled text within the effective range
            $textX = $effectiveStartX + ($maxWidth - $newTextWidth) / 2;
    
            // Add text with adjusted font size (centered)
            imagettftext($image, $newFontSize, 0, $textX, $y, $fontColor, $fontFile, $text);
        } else {
            // Calculate X-coordinate to center text within the effective range
            $textX = $effectiveStartX + ($maxWidth - $textWidth) / 2;
    
            // Add text to the image with original font size (centered)
            imagettftext($image, $fontSize, 0, $textX, $y, $fontColor, $fontFile, $text);
        }
    }

    public function getInitials($string) {
        if (preg_match('/\(([^)]+)\)/', $string, $matches)) {
            // Extract first letters inside brackets
            preg_match_all('/\b\w/', $matches[1], $letters);
            return strtoupper(implode('', $letters[0]));
        } else {
            // Extract first letters of words outside brackets (ignore special chars)
            preg_match_all('/\b[a-zA-Z0-9]/', $string, $letters);
            return strtoupper(implode('', $letters[0]));
        }
    }

    public function generateCertificate() {
        // Check dependencies
        $this->checkDependencies();

        // Validate and sanitize input
        $userImagePath = isset($_FILES['user_image']['tmp_name']) ? $_FILES['user_image']['tmp_name'] : 'pictures/1050.jpg';
        $mainImagePath = 'mainf.png';

        // Validate image paths
        if (empty($userImagePath) || empty($mainImagePath)) {
            header('HTTP/1.1 400 Bad Request');
            exit('Image paths not provided.');
        }

        // Load images
        $userImage = $this->loadImage($userImagePath);
        $mainImage = $this->loadImage($mainImagePath);

        // Resize user image
        $stampWidth = 1 * 220; // Convert inches to pixels (150 pixels per inch for smaller size)
        $stampHeight = 1 * 250;
        $userImageResized = $this->resizeImage($userImage, $stampWidth, $stampHeight);

        // Place resized user image onto main image
        $userImageX = imagesx($mainImage) - $stampWidth - 130; // Right corner position
        $userImageY = $stampWidth + 160; // Top corner position
        imagecopy($mainImage, $userImageResized, $userImageX, $userImageY, 0, 0, $stampWidth, $stampHeight);

        // Add fixed position text
        $textColor = imagecolorallocate($mainImage, 0, 0, 0); // Black color for text
        $textFont = __DIR__ . "/fonts/times.ttf"; // Regular
        $textFontBold = __DIR__ . "/fonts/timesbd.ttf"; // Bold


        // Fetch texts from $_POST or provide defaults
        // $fixedText1 = isset($_POST['serial']) ? $_POST['serial'] : 'Null';
        $candidateId = isset($_POST['candidate_id']) ? $_POST['candidate_id'] : 'Null';
        $fixedText3 = isset($_SESSION['batch']) ? $_SESSION['batch'] : 'Null';
        $currentDate = isset($_SESSION['End_Date']) ? $_SESSION['End_Date'] : date('d-m-Y'); // Use session end date or today
        $currentDate = str_replace('/', '-', $currentDate);
        $endDateTimestamp = strtotime($currentDate);
        if ($endDateTimestamp === false) {
            $endDateTimestamp = time();
        }
        $endYear = date('Y', $endDateTimestamp);
        $fixedText2 = strtoupper(date('M', $endDateTimestamp));
        $endMonth = date('m', $endDateTimestamp);
        
        if ($endMonth <= 3) {
            // If the month is Jan, Feb, or March, the financial year starts in the previous year
            $startYear = $endYear - 1;
        } else {
            // Otherwise, the financial year starts in the current year
            $startYear = $endYear;
        }
        
        // Format as YYYY-YY (e.g., 2024-25)
        $fixedText4 = $startYear . '-' . substr(($startYear + 1), -2);
        
        $fixedText5 = date('d-m-Y', $endDateTimestamp); // Store the exact end date        
        $rollno = isset($_POST['roll_no']) ? $_POST['roll_no'] : 'Null';
        $programCode = $this->getInitials($_SESSION['Training']);
        if (strlen($rollno) == 1) {
            $newrollno = '0' . $rollno; // Append '0' before single-digit roll numbers
        }else {
            $newrollno = $rollno;
        }
        $refText = "RUD/MDU/".$fixedText2."/"."EDP"."/".$fixedText3."/".$candidateId."/".$fixedText4;
        $this->addFixedText($mainImage, $fixedText3 . '-' . $candidateId, $textFontBold, $textColor, 24, 250, 779);
        $this->addFixedText($mainImage, $refText, $textFontBold, $textColor, 24, 250, 832);
        // $this->addFixedText($mainImage, $fixedText3, $textFontBold, $textColor, 20, 648, 812); // 562 812
        // $this->addText($mainImage, $fixedText3, $textFontBold, $textColor, 20, 562, 652, 812);
        // $this->addFixedText($mainImage, $fixedText3, $textFontBold, $textColor, 20, 562, 812); // 562 812
        // $this->addFixedText($mainImage, $fixedText4, $textFontBold, $textColor, 20, 670, 812);
        $this->addFixedText($mainImage, $fixedText5, $textFontBold, $textColor, 24, 230, 1217);

        // Fetch variable position texts from $_POST or provide defaults
        $varText1 = isset($_POST['customer_name']) ? $_POST['customer_name'] : 'Null';
        $varText2 = isset($_POST['Dependent_name']) ? $_POST['Dependent_name'] : 'Null';
        $varText3 = isset($_POST['address']) ? $_POST['address'] : 'Null';
        $varText4 = isset($_SESSION['Training']) ? $_SESSION['Training'] : 'Null';
        $varText5 = isset($_SESSION['Start_Date']) ? $_SESSION['Start_Date'] : 'Null';
        $varText6 = isset($_SESSION['End_Date']) ? $_SESSION['End_Date'] : 'Null';
        $varText7 = isset($_SESSION['Sponsors']) ? $_SESSION['Sponsors'] : 'Null';

        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 650, 800);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1150, 800);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1370, 800);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 250, 875);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1640, 875);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1890, 800);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 880, 952);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1890, 952);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 565, 1026);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1150, 1026);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1230, 1026);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1890, 1026);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 750, 1100);
        // $this->addFixedText($mainImage, "THIS IS A SAMPLE", $textFontBold, $textColor, 24, 1890, 1100);

        // Add variable position texts with alignment
        $this->addText($mainImage, strtoupper($varText1), $textFont, $textColor, 24, 645, 1257, 913);
        $this->addText($mainImage, strtoupper($varText2), $textFont, $textColor, 24, 1370, 1890, 913);
        $this->addText($mainImage, strtoupper($varText3), $textFont, $textColor, 24, 300, 1630, 960);
        $this->addText($mainImage, strtoupper($varText4), $textFontBold, $textColor, 24, 880, 1890, 1006);
        $this->addText($mainImage, $varText5, $textFont, $textColor, 24, 565, 1150, 1058);
        $this->addText($mainImage, $varText6, $textFont, $textColor, 24, 1230, 1890, 1058);
        $this->addText($mainImage, strtoupper($varText7), $textFontBold, $textColor, 24, 750, 1890, 1103);

        // Determine the next available image name in the batch folder
        // $imageCount = count(glob($this->batchFolder . '*.png')) + 1;
        $outputImageFile = $this->batchFolder . $rollno . '.png';

        // Save the final image to the batch folder
        imagepng($mainImage, $outputImageFile);

        // Free up memory
        imagedestroy($mainImage);
        imagedestroy($userImage);
        imagedestroy($userImageResized);

        // Redirect to data.php
        header("Location: data.php");
        exit();
    }
}

// Instantiate Certificates class and execute generateCertificate method
$certificates = new Certificates();
$certificates->generateCertificate();
?>
