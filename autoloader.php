<?php

spl_autoload_register( function ( $class ) {
    // Handle 'CoolKidsApp' namespaces
    $namespaces = [
        'CoolKidsApp\\' => __DIR__ . '/',
    ];

    foreach ( $namespaces as $rootNamespace => $baseDir ) {
        // Check if the class uses the root namespace
        if ( strpos( $class, $rootNamespace ) === 0 ) {
            // Remove the root namespace from the class name
            $relativeClass = str_replace( $rootNamespace, '', $class );

            // Convert namespace separators to directory separators
            $classPath = str_replace( '\\', DIRECTORY_SEPARATOR, $relativeClass ) . '.php';

            // Handle different directory structures
            if ( $rootNamespace === 'CoolKidsApp\\' ) {
                // Attempt to load the file from various subdirectories (Controller, Model, Service, Enum)
                $folders = ['Controller', 'Model', 'View', '']; // '' for the base directory
                
                foreach ( $folders as $folder ) {
                    $filePath = $baseDir . ( $folder ? $folder . '/' : '' ) . $classPath;
                    
                    if ( file_exists( $filePath ) ) {
                        require $filePath;
                        return; // Stop further processing once the file is found
                    }
                }
            }

            // Debugging: Log if the file was not found
            error_log( "File not found: " . $filePath );
        }
    }
});
