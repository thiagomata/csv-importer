<?php

namespace App\Importer\Csv;

use App\Importer\Interfaces\Target;
use App\Importer\Interfaces\Field;

class CsvTarget implements Target {
    
    private $strFileName = "out.csv";
    
    private $arrFields = [];
    
    private $arrNames = [];
    
    private $objSource;

    private $booPrintHeader = true;
    
    private $strColumnDelimiter = ";";
    
    public function setFileName(string $fileName): Target {
        $this->strFileName = $fileName;
        return $this;
    }
    
    public function setColumnDelimiter(string $columnDelimiter) {
        $this->strColumnDelimiter = $columnDelimiter;
    }
    
    public function addFieldAtPosition(Field $field, int $position): Target {
        $this->checkSource($field);
        $this->arrFields[$position] = $field;
        $this->arrNames[$position] = $field->getName();
        return $this;
    }

    public function addFieldAtPositionWithName(Field $field, int $position, string $fieldName): Target {
        $this->checkSource($field);
        $this->arrFields[$position] = $field;
        $this->arrNames[$position] = $fieldName;
        return $this;
    }

    public function addFieldNextPositionWithName(Field $field, string $fieldName): Target {
        $this->checkSource($field);
        $this->arrFields[] = $field;
        $this->arrNames[] = $fieldName;
        return $this;
    }

    private function checkSource(CsvField $field) {
        if( $this->objSource == null ) {
            $this->objSource = $field->getSource();
        } else {
            if( $this->objSource !== $field->getSource() ) {
                throw new TargetException("Unable to accept fields from different sources");
            }
        }
    }
    
    public function getSource(): CsvSource {
        return $this->objSource;
    }

    public function getCsvContent() {
        $objMemory = fopen('php://memory', 'r+');
        $this->saveToSource($objMemory);
        rewind($objMemory);
        $strContents = stream_get_contents($objMemory);
        return rtrim($strContents);
    }
    
    public function saveToFile() {
        $objFile = fopen( $this->strFileName, "a+");
        $this->saveToSource($objFile);
    }
    
    public function saveToSource($objFile) {
        if( $this->booPrintHeader ) {
            fputcsv($objFile, $this->arrNames, $this->strColumnDelimiter);
        }
        while( ! $this->getSource()->eof() ) {
            $this->getSource()->loadLine();
            $arrDestLine = [];
            foreach( $this->arrFields as $intKey => $objDestField  ) {
                $arrDestLine[ $intKey ] = $objDestField->getCurrentValue();
            }
            fputcsv($objFile, $arrDestLine, $this->strColumnDelimiter);
        }        
    }
}