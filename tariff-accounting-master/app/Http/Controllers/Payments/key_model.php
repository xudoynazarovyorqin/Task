<?php
    if( $application = \App\Application::where('console_number', $key)->first() ) {
        if( $contract_client = $application->contract_client ) {
            return $application;
        }
        else {
            return null;
        }
    }
    else {
        return null;
    }
