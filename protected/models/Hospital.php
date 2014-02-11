<?php
use Doctor\Therapeutist;

/**
 * Hospital.php class file.
 * 
 * @author Ruslan Fadeev <fadeevr@gmail.com>
 * @link http://yiifad.ru/
 * @copyright 2012-2014 Ruslan Fadeev
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
 

class Hospital implements HospitalInterface {
   public static $doctorClasses = ['Oculist', 'Otorhinolaryngologist', 'Therapeutist'];

    /** @var Patient[] */
    private $patient;

    /** @var Doctor[] */
    private $doctor;

    /** @var Sickness[] */
    private $sickness;

    public function __construct()
    {
        $this->patient = new CList();
        $this->doctor = new CList();

        $patientSickness = [
            'Bregnev'  => ['Eyesight'],
            'Pugachev' => ['Ear', 'Throat'],
            'Ivanov'   => ['Head', 'Throat'],
            'Bolnoy'   => ['Head'],
            'Babushka' => ['Ear', 'Eyesight', 'Throat', 'Head'],
        ];

        foreach ($patientSickness as $name => $sickness) {
            $patient = new Patient($name);
            foreach ($sickness as $sicknessName) {
                $patient->addSickness($sicknessName);
            }
            $this->patient->add($patient);
        }

        foreach (self::$doctorClasses as $doctorType) {
            $this->doctor->add(Doctor::create($doctorType));
        }
    }

    public function open()
    {
        echo '<hr /><b>запишем пациентов к свободным врачам через терапевта</b><br />';
        foreach ($this->getPatientByStatus() as $patient) { // новых посетителей к терапевту
            $this->getTherapeutist()->getDirectForPatient($patient, $this->doctor);
        }
        echo '<br /><b>запись завершена </b><hr />';
    }

    public function working()
    {
        /*foreach ($this->getDoctorByStatus() as $doctor) {
            foreach ($this->getPatientByStatus(StateInterface::STATE_PATIENT_WAITING) as $patient) {
                foreach ($doctor->getSickness() as $sickness) {
                    foreach ($patient->getSickness() as $pSickness) {
                        if ($doctor->hasPatient()) {
                            echo 'Doctor has patient ' . $currentPatient->getName();
                            continue;
                        }
                        if ($sickness == $pSickness) {echo $sickness->getName();exit('die');
                            //$doctor->attach($patient);
                        }
                    }
                }
            }
            #$doctor->doUpdate();
        }*/
        #print_r($this->doctor);
    }

    public function statistics()
    {
        echo 'Pacient List: <hr />';
        foreach ($this->patient as $patient) {
            echo $patient->card() . '<br /><br />';
        }
        echo '<hr />';

        foreach (['Oculist', 'Otorhinolaryngologist', 'Therapeutist'] as $doctorType) {
            $doctors[] = Doctor::create($doctorType);
        }
        echo 'Doctor List: <br /><br />';
        foreach ($this->doctor as $doctor) {
            echo '<b>' . $doctor->getName() . '</b> ';
            echo $state = $doctor->getState() .  '<br /><br />';
        }
    }

    /**
     * @return Therapeutist|null
     */
    protected function getTherapeutist()
    {
        foreach ($this->doctor as $doctor) {
            if ($doctor::IS_THERAPEUTIST) {
                return $doctor;
            }
        }
        return null;
    }

    /**
     * @param string $state
     * @return Patient[]
     */
    protected function getPatientByStatus($state = StateInterface::STATE_PATIENT_NEW)
    {
        $patients = [];
        foreach ($this->patient as $patient) {
            if ($patient->getState() == $state) {
                $patients[] = $patient;
            }
        }
        return $patients;
    }

    /**
     * @param string $state
     * @return Doctor[]
     */
    protected function getDoctorByStatus($state = StateInterface::STATE_DOCTOR_FREE)
    {
        $doctors = [];
        foreach ($this->doctor as $doctor) {
            if ($doctor->getState() == $state) {
                $doctors[] = $doctor;
            }
        }
        return $doctors;
    }
} 