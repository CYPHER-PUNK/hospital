<?php
/**
 * Therapeutist.php class file.
 * 
 * @author Ruslan Fadeev <fadeevr@gmail.com>
 * @link http://yiifad.ru/
 * @copyright 2012-2014 Ruslan Fadeev
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
 

namespace Doctor;


class Therapeutist extends \Doctor {
    const IS_THERAPEUTIST = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function send()
    {

    }

    public function getDirectForPatient(\Patient $patient, \CList $doctor)
    {
        #echo PHP_EOL . $this->getName() . ' <- ' . $patient->getName() . PHP_EOL;
        foreach ($patient->getSickness() as $patientSickness) {
            /** @var \Doctor $doc */
            foreach ($doctor as $doc) {
                foreach ($doc->getSickness() as $doctorSickness) {
                    if ($patientSickness == $doctorSickness) {
                        $patient->currentSickness = $patientSickness;
                        $patient->toDoctor($doc);
                    }
                }
            }
            if (!$patient->currentSickness) {
                $patient->noDoctor($patientSickness);
            }
        }
    }
}
