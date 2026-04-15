<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Semantic;

/**
 * Chief Complaint - free-text symptom description provided by the patient
 *
 * No structural validation is performed (any non-empty complaint is accepted
 * by the downstream JTAS protocol). Declared here so the Be Framework's
 * semantic validator can resolve the variable name and skip validation
 * without emitting "not registered in ontology namespace" notices.
 *
 * @link https://schema.org/MedicalSymptom
 */
final class ChiefComplaint
{
}
