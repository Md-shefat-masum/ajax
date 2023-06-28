<?php
$form_data = "------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"full_name\"
    
    af asdf
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"email\"
    
    afasdf@gmail.com
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"dob\"
    
    2023-05-31
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"user_role\"
    
    admin
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"gender\"
    
    male
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"courses[web][]\"
    
    web_design
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"courses[web][]\"
    
    graphics_design
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"description\"
    
    demo description
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx
    Content-Disposition: form-data; name=\"image\"; filename=\"\"
    Content-Type: application/octet-stream
    
    
    ------WebKitFormBoundary40vtfCc8g3HUy7Bx--";

echo "<pre>";

// Define the boundary string
$boundary = "------WebKitFormBoundary40vtfCc8g3HUy7Bx";

// Split the string by the boundary string
$fields = explode($boundary, $form_data);


// Remove the first and last elements, which are empty or contain only --
array_shift($fields);
array_pop($fields);

// Initialize an empty associative array
$array = array();

// Loop through the fields
foreach ($fields as $field) {
    // Split the field by a blank line
    list($headers, $value) = explode("\r\n\r\n", $field, 2);

    // Parse the headers to get the name, filename, and content type of the field
    preg_match('/name="([^"]+)"/', $headers, $name_match);
    preg_match('/filename="([^"]*)"/', $headers, $filename_match);
    preg_match('/Content-Type: ([^\r\n]+)/', $headers, $content_type_match);


    // Get the name from the first capture group of the name match
    $name = $name_match[1];

    // Get the filename from the first capture group of the filename match, or null if not found
    $filename = isset($filename_match[1]) ? $filename_match[1] : null;

    // Get the content type from the first capture group of the content type match, or null if not found
    $content_type = isset($content_type_match[1]) ? $content_type_match[1] : null;

    // Store the name and value pair in the associative array
    // If the name ends with [], it means it is an array field, so append the value to an array with that name
    if (substr($name, -2) == "[]") {
        // Remove the [] from the name
        $name = substr($name, 0, -2);
        // Initialize the array if not set
        if (!isset($array[$name])) {
            $array[$name] = array();
        }
        // Append the value to the array
        $array[$name][] = $value;
    } else {
        // Otherwise, just set the value for the name
        $array[$name] = $value;
    }
}

// Return or print the associative array
print_r($array);
