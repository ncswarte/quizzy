<?php

class AdminController extends AppController {

	public function isAuthorized($user) {
		// Only admin (that's the default)
		return parent::isAuthorized($user);
	}

	public function index() {
		$this->set('title_for_layout', __('Administration Area') );
		$this->loadModel('Research');
		$this->set('arrResearches', $this->Research->loadAllData() );

		if ( !empty($this->data) ) {
			$this->Session->write('User.currentResearch', $this->data['research']);
		}
		
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		
		if( !empty( $tempSelectedResearch ) ) {
			
			$this->loadModel('ResearchAssistant');
			$this->loadModel('ResearchPatients');
			$this->loadModel('PatientQuiz');
			$this->loadModel('Assistant');
			$this->loadModel('Patient');
			$this->loadModel('Quiz');
			
			$tempPatientList 		= $this->ResearchPatients->loadResearchPatients( $tempSelectedResearch) ;
			$tempQuizzes	 		= $this->Quiz->loadResearchQuizzes( $tempSelectedResearch );
			$tempResearchQuizzes	= array();
			$tempPatientsQuizzes 	= array();
			
			// Keep this research's quizzes to filter out patient's quizzes
			foreach( $tempQuizzes as $currQuiz ) {
				$tempResearchQuizzes[ $currQuiz['Quiz']['quizID'] ] = 1;
			}
			
			// Now load all patient's quizzes BUT filter out those that aren't relevant to the current research
			foreach( $tempPatientList as $currPatient ) {
				$tempPatientsQuizzes[$currPatient] = $this->PatientQuiz->loadPatientQuizzes( $currPatient );
				foreach( $tempPatientsQuizzes[$currPatient] as $currQuiz => $currQuizV ) {
					if( !isset( $tempResearchQuizzes[ $currQuizV['0'] ] ) )
						unset( $tempPatientsQuizzes[$currPatient][$currQuiz] );
				}
			}
			
			// Load assistant data
			$tempAssistantsData = $this->Assistant->loadAllData();
			$tempAssistants		= array();
			foreach( $tempAssistantsData as $curr ) {
				$tempAssistants[ $curr['Assistant']['assistantID'] ] = $curr['Assistant']['assistantName'];
			}
			
			$this->set('arrPatients', $this->Patient->loadPatientList($tempPatientList) );
			$this->set('arrAllAssistants', $tempAssistants );
			$this->set('arrAssistants', $this->ResearchAssistant->loadResearcheAssistants( $tempSelectedResearch) );
			$this->set('arrPatientsQuizzes', $tempPatientsQuizzes );
			$this->set('arrQuizzes', $tempQuizzes );
			
		} else {
			// They must choose one!
		}
	}
	
	// Add or edit a patient entry
	public function addPatient( $id = null ) {
		
		$this->set('title_for_layout', __('Add a patient') );
		if( $this->Session->check('User.currentResearch') == false ) {
			$this->redirect(array('action' => 'index'));
		}
		
		$this->loadModel('Patient');
		$this->loadModel('User');
		$this->loadModel('ResearchPatients');
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		
		if ( !empty($this->data) ) {
		
			$tempUserObject = $this->data['Patient'];
			unset($this->request->data['Patient']['password1']);
			unset($this->request->data['Patient']['password2']);
			
			$this->Patient->save($this->data);
			
			// A new patient or not?
			if( empty( $this->data['Patient']['patID'] ) ) {
			
				// New kid on the block, should create a user for them
				$this->User->save( array(
					'id' => $this->Patient->id,
					'username' => $this->Patient->id,
					'role' => 'patient',
					'password' => '123456'
				 ) );
				
				// They also belong to the current research...
				$this->ResearchPatients->save( array(
					'ResearchPatients' => array(
						'researchID' => $tempSelectedResearch,
						'patID' => $this->Patient->id )
				) );
				$this->Session->setFlash(__('Patient added successfully!', true));			
			
			// He's a old folk...
			} else {
				
				// We've got a new password on our hands my dear!
				if( !empty($tempUserObject['password1']) && !empty($tempUserObject['password2']) ) {
					$this->User->id = $tempUserObject['patID'];
					
					// Make sure they match
					if( $tempUserObject['password1'] != $tempUserObject['password2'] ) {
						$this->Session->setFlash(__('ERROR entered passwords do not match!', true));	
						return;
					}
					
					// Try save it please
					if( !($this->User->save(array('password' => $tempUserObject['password1'] ) )) ) {
						//$this->User->invalidFields() will containt the actual problematic fields
						$this->Session->setFlash(__('ERROR updating password (please check length)!', true));	
						return;
					} else {
						$this->Session->setFlash(__('Patient updated successfully (including password)!', true));	
					}
					
				} else {
					$this->Session->setFlash(__('Patient updated successfully!', true));	
				}
			}

			// Either way we're done :)
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		
		} elseif( isset($id) ) {
			$this->data = $this->Patient->findById($id);
			
			// Invalid ID/nothing there...
			if( empty( $this->data ) )
				return;
			
			//Fetch files
			$this->loadModel('PatientFiles');
			$this->set('fileData', $this->PatientFiles->loadPatientFiles( $id ) );
			$this->set('title_for_layout', __('Update a patient') );
		} 
	}
	
	// Add a patient file
	public function addPatientFile() {
	
		if( $this->request->data['PatientFiles']['upload']['name'] != "" ) {
			$this->request->data['FINALPATH'] = $this->handleUpload( $this->data['PatientFiles']['upload'], 'patient' );	
			$this->loadModel('PatientFiles');
			$this->PatientFiles->create();
			$this->PatientFiles->save(
				array( 'patID' => $this->data['Patient']['patID'],
						'fileDescription' => $this->data['PatientFiles']['fileDescription'],
						'fileName' => $this->request->data['FINALPATH'],
						'dateAdded' => date('Y-m-d H:i:s')) 
				);
			
			$this->Session->setFlash(__('File added successfully!', true));
		} else {
			$this->Session->setFlash(__('Error - no file specified!', true));
		}
		$this->redirect(array('controller' => 'Admin', 'action' => 'addPatient', $this->data['Patient']['patID'] ) );
	}
	
	// Delete a patient file
	public function deleteFile ( $patID, $fileID ) {
		$this->loadModel('PatientFiles');
		$tempFile = $this->PatientFiles->loadFileID( $fileID );
		
		// First delete the record
		if ( $this->PatientFiles->delete( $tempFile['0']['PatientFiles']['fileID'] ) ) {
			
			// If we succeeded attempt to remove the file
			if( unlink( WWW_ROOT . 'uploads/patient/' . $tempFile['0']['PatientFiles']['fileName'] ) ) {
				$this->Session->setFlash(__('File removed successfully!', true));
			} else {
				$this->Session->setFlash(__('Error removing file!', true));
			}
		} else {
			$this->Session->setFlash(__('Error removing database entry!', true));
		}
		
		// This would go back to the edit patient after removing the file
		$this->redirect(array('controller' => 'Admin', 'action' => 'addPatient', $patID ) );
	}
	
	// Add or edit an assistant entry
	public function addAssistant( $id = null ) {
	
		$this->set('title_for_layout', __('Add an assistant') );
		$this->loadModel('User');
		$this->loadModel('Assistant');
		$this->loadModel('ResearchAssistant');
		
		if( $this->Session->check('User.currentResearch') == false ) {
			$this->redirect(array('action' => 'index'));
		}
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		
		if ( !empty($this->data) ) {
		
			$tempUserObject = $this->data['Assistant'];
			unset($this->request->data['Assistant']['password1']);
			unset($this->request->data['Assistant']['password2']);
			
			$this->Assistant->id = $this->data['Assistant']['assistantID'];
			$this->Assistant->save($this->data);
			
			// A new assistant or not?
			if( empty( $this->data['Assistant']['assistantID'] ) ) {
			
				// New helper on the block, should create a user for them
				$this->User->save( array(
					'id' => $this->Assistant->id,
					'username' => $this->Assistant->id,
					'role' => 'assistant',
					'password' => '123456'
				 ) );
				
				// They also belong to the current research...
				$this->ResearchAssistant->save( array(
					'ResearchAssistant' => array(
						'researchID' => $tempSelectedResearch,
						'assistantID' => $this->Assistant->id )
				) );
				$this->Session->setFlash(__('Assistant added successfully!', true));			
			
			// He's a old one...
			} else {
				
				// We've got a new password on our hands my dear!
				if( !empty($tempUserObject['password1']) && !empty($tempUserObject['password2']) ) {
					$this->User->id = $tempUserObject['assistantID'];
					
					// Make sure they match
					if( $tempUserObject['password1'] != $tempUserObject['password2'] ) {
						$this->Session->setFlash(__('ERROR entered passwords do not match!', true));	
						return;
					}
					
					// Try save it please
					if( !($this->User->save(array('password' => $tempUserObject['password1'] ) )) ) {
						$this->Session->setFlash(__('ERROR updating password (please check length)!', true));	
						return;
					} else {
						$this->Session->setFlash(__('Assistant updated successfully (including password)!', true));	
					}
					
				} else {
					$this->Session->setFlash(__('Assistant updated successfully!', true));	
				}
			}
			
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		
		} elseif( isset($id) ) {
			$this->data = $this->Assistant->findById($id);
			
			// Invalid ID/nothing there...
			if( empty( $this->data ) )
				return;
		} 
	}
	
	// Delete an assistant
	public function deleteAssistant( ) {
		
		$this->loadModel('Assistant');
		$this->loadModel('ResearchAssistant');
		
		if( isset($this->data['fldAssistantID']) ) {
			$this->ResearchAssistant->deleteAll( array('ResearchAssistant.assistantID' => $this->data['fldAssistantID'] ), false);
			$this->Assistant->delete( $this->data['fldAssistantID'] );
			$this->Session->setFlash(__('Assistant removed successfully!', true));			
		}
		
		$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
	}
	
	// Add or edit a research entry
	public function addResearch( $id = null ) {
		$this->loadModel('Research');
		$this->set('title_for_layout', __('Add a research') );
		
		if ( !empty($this->data) ) {
			$this->Research->save($this->data);
			
			$tempNewResearchID = $this->Research->getLastInsertID();
			$this->Session->write('User.currentResearch', $tempNewResearchID);
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		
		} elseif( isset($id) ) {
			$this->data = $this->Research->findById($id);
		} 
	}
	
	// Import an existing patient
	public function importPatient() {
		$this->set('title_for_layout', __('Import an existing patient') );
		if( $this->Session->check('User.currentResearch') == false ) {
			$this->redirect(array('action' => 'index'));
		}
		
		if ( !empty($this->data) ) {
			$this->loadModel('ResearchPatients');
			
			// Already part of this one?
			if( $this->ResearchPatients->isPatientOfResearch( $this->data['patID'] , $this->Session->read('User.currentResearch') ) == true ) {
				$this->Session->setFlash(__('Patient already associated with research!', true));			
			} else {
				
				// We're ok, continue...
				$this->ResearchPatients->save( array(
					'patID' => $this->data['patID'],
					'researchID' => $this->Session->read('User.currentResearch') ) );
				$this->Session->setFlash(__('Patient imported successfully!', true));			
				$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
			}
		}
		
		$this->loadModel('Research');
		$this->loadModel('ResearchPatients');
		$this->loadModel('Patient');
		
		// Let's only list the additional patients [i.e. not already part of this research]
		$tempCurrentResearchPats = array();
		$tempAllPats = $this->Patient->loadAllData();
		foreach( $this->ResearchPatients->loadResearchPatients(  $this->Session->read('User.currentResearch') ) as $currPat ) {
			$tempCurrentResearchPats[$currPat] = 1;
		}
		foreach( $tempAllPats as $currPat => $currPatV) {
			if( isset( $tempCurrentResearchPats[ $currPatV['Patient']['patID'] ] ) )
				unset( $tempAllPats[$currPat] );
		}
		
		$this->set('arrPatients', $tempAllPats );
	}
	
	// Add a new quiz (quiz builder)
	public function addQuiz() {
		
		$this->set('title_for_layout', __('Add a quiz') );
		$this->set('prevQNum', 0);
		$this->loadModel('Quiz');
		$this->loadModel('Questions');
		
		//is it a POST?
		if ($this->request->is('post')) {
		
			$tempArrQuestions = array();
			
			// We're not yet done, need to keep on building!
			if( $this->request->data['fldFlagDone'] == '0' ) {
				
				// Handle the upload stuff
				if( $this->request->data['upload']['name'] != "" ) {
					$this->request->data['fldQuizData'] = trim($this->request->data['fldQuizData']);
					$this->request->data['FINALPATH'] = $this->handleUpload( $this->data['upload'] , 'question' );
					$this->request->data['fldQuizData'] .= "IMG=".$this->request->data['FINALPATH']."\n";
				}
				
				$this->set('prevQuiz', $this->request->data['fldQuizData']);
				$this->set('prevTitle', $this->request->data['fldQuizTitle']);
				
				preg_match_all('/(Q\d+);;/', $this->request->data['fldQuizData'], $tempMatches );
				$this->set('prevQNum', count($tempMatches[0]) );
				$this->Session->setFlash(__('Question ').(count($tempMatches[0])).__(' added successfully, continue building'), true);	
			
			// Time to save this bad boy!
			} else {
			
				// Handle the upload stuff - the last question might have an image my dear!
				if( $this->request->data['upload']['name'] != "" ) {
					$this->request->data['fldQuizData'] = trim($this->request->data['fldQuizData']);
					$this->request->data['FINALPATH'] = $this->handleUpload( $this->data['upload'] , 'question' );
					$this->request->data['fldQuizData'] .= "IMG=".$this->request->data['FINALPATH']."\n";
				}
			
				// Save the quiz
				$this->saveQuiz( $this->request->data['fldQuizTitle'] );
				$tempNewQuizID = $this->Quiz->getLastInsertID();
				
				foreach (preg_split("/\n/", $this->request->data['fldQuizData']) as $currLine) {
					if( strlen( $currLine ) < 1 )
						continue;
						
					// [0] = Q num		[1] = Text		[2] = Type		[3] = Data	
					$thisLine = preg_split("/;;/", $currLine );
					$tempImage = NULL;
					
					// Handle image if it's listed
					if( count($thisLine) > 4 ) {
						$tempImage = trim($thisLine[4]);
						$tempImage = preg_replace( "/^IMG=/", "", $tempImage);
					}
					
					// We handle MATRIX differently, we save all the options for each Q to allow each one's response to be logged
					// as a question while allowing re-grouping of them when displaying
					if( $thisLine[2] == "MATRIX" ) {
						
						// [1] has the Options, [2] has the Q
						preg_match( "/;;MATRIX;;(.+)@@\|\|(.+);;/", $currLine, $thisParts);
						$thisQs = preg_split( "/@@/", $thisParts[2]);
						
						foreach ( $thisQs as $currQ ) {
							if( strlen( $currQ ) < 1 )
								continue;
						
							$currQ = preg_replace( '/^MATRIXQ=/', '', $currQ);
							
							// Associate the question with the quiz
							$this->saveQuestion( $currQ, "MATRIX", $thisParts[1], $tempImage, $tempNewQuizID );
						}
					
					// Standard non-MATRIX question ahead
					} else {
						// Associate the question with the quiz
						$this->saveQuestion( $thisLine[1], $thisLine[2], $thisLine[3], $tempImage, $tempNewQuizID );
					}
				}
				
				
				
				$this->Session->setFlash(__('Quiz added successfully!', true));	
				$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
			}
			
		} else {
			//No problems here
			$this->set('prevQuiz', '');
			$this->set('prevTitle', '');
		}
	}
	
	// Manage a patient's quiz allocation
	public function patientQuiz( $patID ) {
		$this->set('title_for_layout', __('Manage patient quiz allocation') );
		$this->loadModel('Quiz');
		$this->loadModel('Patient');
		$this->loadModel('PatientQuiz');
		
		// Selection submitted
		if( !empty( $this->data ) ) {
			$this->savePatientQ( $this->data['fldPatientID'], $this->data['fldQuizList'] );
			$this->Session->setFlash(__('Quiz allocation saved successfully!', true));			
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		
		} else {
			$this->set('listQuiz', $this->Quiz->loadResearchQuizzes( $this->Session->read('User.currentResearch') ) );
			$this->set('listPatQ', $this->PatientQuiz->loadPatientQuizzes( $patID ) );
			$this->set('patInfo', $this->Patient->loadPatient( $patID )['0'] );
			
			// Reverse lookup list
			$tempPatientQuizList = $this->PatientQuiz->loadPatientQuizzes( $patID );
			$tempQuizList = array();
			foreach( $tempPatientQuizList as $tempCurrQuiz ) {
				array_push( $tempQuizList, $tempCurrQuiz['0'] );
			}
			$this->set('quizInfo', $this->Quiz->loadQuizListFiltered( $tempQuizList ) );
		}
	}
	
	// Manage research's assistant allocation
	public function manageAssistants( ) {
		$this->set('title_for_layout', __('Manage assistant allocation') );
		
		$this->loadModel('Research');
		$this->loadModel('ResearchAssistant');
		$this->loadModel('Assistant');
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		
		// Selection submitted
		if( !empty( $this->data ) ) {
			$this->saveResearchAssistant( $this->data['fldAssistantList'] );
			$this->Session->setFlash(__('Assistant allocation saved successfully!', true));			
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
			return;
		}
		
		$tempAssistants 		= array();
		$tempAssistantsInfo 	= array();
		
		$tempAllAssistants = $this->Assistant->loadAllData();
		$tempResearchAssistants = $this->ResearchAssistant->loadResearcheAssistants( $tempSelectedResearch );
		
		// This researche's assistants
		foreach( $tempResearchAssistants as $currAssistant ) {
			$tempAssistants[$currAssistant['ResearchAssistant']['assistantID']] = 1;
		}
		
		// All of 'em
		foreach( $tempAllAssistants as $currAssistant ) {
			if( !isset($tempAssistants[$currAssistant['Assistant']['assistantID']]) )
				$tempAssistants[$currAssistant['Assistant']['assistantID']] = 0;
			
			$tempAssistantsInfo[$currAssistant['Assistant']['assistantID']] = $currAssistant['Assistant']['assistantName'];
		}
				
		$this->set('arrAssistants', $tempAssistants );
		$this->set('arrAssistantsInfo', $tempAssistantsInfo );
		$this->set( 'researchInfo', $this->Research->loadResearch( $tempSelectedResearch )['0'] );
	}
	
	// Manage research's patient allocation (i.e. add existing?)
	public function managePatients( ) {
		
		$this->set('title_for_layout', __('Manage patient allocation') );
		
		$this->loadModel('Quiz');
		$this->loadModel('Patient');
		$this->loadModel('Research');
		$this->loadModel('ResearchPatients');
		$tempSelectedResearch 	= $this->Session->read('User.currentResearch');
		$tempPatientList 		= $this->ResearchPatients->loadResearchPatients( $tempSelectedResearch ) ;
		$tempAllPatients		= $this->Patient->loadAllData();
		$tempResearchPatients 	= array();
		$tempResearchNonPatients= array();
		
		foreach( $tempPatientList as $currentPatient ) {
			$tempResearchPatients[$currentPatient] = '';
		}
		
		foreach( $tempAllPatients as $currentPatient ) {
			if( isset($tempResearchPatients[$currentPatient['Patient']['patID']]) ) {
				$tempResearchPatients[$currentPatient['Patient']['patID']] = $currentPatient['Patient'];
			} else {
				$tempResearchNonPatients[$currentPatient['Patient']['patID']] = $currentPatient['Patient'];
			}
		}
		
		// Selection submitted
		if( !empty( $this->data ) ) {
			$this->saveResearchPatients( $this->data['fldPatientList'] );
			$this->Session->setFlash(__('Patient allocation saved successfully!', true));			
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		}

		$this->set( 'researchInfo', $this->Research->loadResearch( $tempSelectedResearch )['0'] );
		$this->set( 'arrResearchPatients', $tempResearchPatients );
		$this->set( 'arrResearchNonPatients', $tempResearchNonPatients );
	}
	
	public function deletePatient( ) {
		
		$this->loadModel('Patient');
		
		if( isset($this->data['fldPatientID']) ) {
			
			// We need to remove: files [DB + actual files], all ResearchPatients, all PatientQuiz and naturally the Patient
			$this->loadModel('ResearchPatients');
			$this->loadModel('PatientFiles');
			$this->loadModel('PatientQuiz');
			$this->loadModel('Patient');
			
			$this->PatientQuiz->deleteAll(  array('PatientQuiz.patID' => $this->data['fldPatientID'] ), false);
			foreach( $this->PatientFiles->loadPatientFiles( $this->data['fldPatientID'] ) as $currFile ) {
				$tempFile = $this->PatientFiles->loadFileID( $currFile['PatientFiles']['fileID'] );
				$this->PatientFiles->delete( $tempFile['0']['PatientFiles']['fileID'] );
				unlink( WWW_ROOT . 'uploads/patient/' . $tempFile['0']['PatientFiles']['fileName'] );
			}
			$this->ResearchPatients->deleteAll( array('ResearchPatients.patID' => $this->data['fldPatientID'] ), false);
			$this->Patient->delete( $this->data['fldPatientID'] );
			
			$this->Session->setFlash(__('Patient removed successfully!', true));
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		}
	}
	
	// Delete quiz
	public function deleteQuiz( ) {
	
		if( isset($this->data['fldQuizID']) ) {
			
			$this->loadModel('Quiz');
			$this->loadModel('Questions');
			$this->loadModel('PatientQuiz');
			
			// Delete all quiz related data (PatientQuiz, Questions, Quiz)
			$this->PatientQuiz->deleteAll(array('PatientQuiz.quizID' => $this->data['fldQuizID'] ), false);
			$this->Questions->deleteAll(array('Questions.quizID' => $this->data['fldQuizID'] ), false);
			$this->Quiz->deleteAll(array('Quiz.quizID' => $this->data['fldQuizID'] ), false);
			
			$this->Session->setFlash(__('Quiz removed successfully!', true));
			$this->redirect(array('controller' => 'Admin', 'action' => 'index' ) );
		}
	}
		
//
// PRIVATE FUNCTIONS:
// ==================
	
	// Save a question
	private function saveQuestion( $qText, $qType, $qData, $qImage, $qQuiz ) {
		
		$this->loadModel('Questions');
		
		$thisQuestion = array( 
			'quizID' => $qQuiz,
			'questionText' => $qText,
			'questionType' => $qType,
			'questionImage' => $qImage,
			'questionData' => $qData );
		
		$this->Questions->create();
		$this->Questions->save($thisQuestion);
		
		return ( $this->Questions->getLastInsertId() );
	}
	
	// Save Quiz
	private function saveQuiz( $quizTitle ) {
	
		$this->loadModel('Quiz');
		
		$thisQuiz = array( 
			'quizTitle' => $quizTitle,
			'researchID' => $this->Session->read('User.currentResearch') );
		
		$this->Quiz->create();
		$this->Quiz->save( $thisQuiz );
	}
	
	// Upload dirty work
	private function handleUpload ( $objFile, $type ) {
		
		// Seperating the patient files and the question images
		$uploadPath = WWW_ROOT . 'uploads/';
		if( $type == "patient" ) {
			$uploadPath .= 'patient/';
		} else {
			$uploadPath .= 'question/';
		}
		
		$file = $objFile;
		$tempExt = strtolower(strrchr($file['name'], '.'));
		
		// Allowed Extensions
		$arrValidExt = array('.png', '.bmp', '.tif', '.tiff', '.jpg', '.jpeg', '.gif', '.txt', '.pdf', '.csv', '.xls', '.xlsx', '.doc', '.docx');
		
		 // Validate Extension
		if(in_array($tempExt, $arrValidExt)) {

			// Work on check exists..
			$tempPrefix = substr($file['name'], 0, -strlen($tempExt));
			$tempi = 0;
			
			// If exists, add/inc a number to it.
			while(file_exists( $uploadPath . $file['name'] )) {
				$file['name'] = $tempPrefix . ++$tempi . $tempExt;
			}
			
			move_uploaded_file($file['tmp_name'], $uploadPath . $file['name']);
			return ($file['name'] );
		}
	}
	
	// Save patient's quiz allocation
	private function savePatientQ( $patID, $strQuizList ) {
		
		// Load original list
		$this->loadModel('PatientQuiz');
		$tempList = $this->PatientQuiz->loadPatientQuizzes( $patID );
		
		// Let's reverse it to make life easier
		$tempPatQuiz = array();
		$tempNewQuiz = array();
		$tempFlagOldQuiz = array();
		foreach( $tempList as $currQuiz ) {
			$tempPatQuiz[$currQuiz[0]] = $currQuiz[1];
		}
		
		foreach( preg_split('/,/', $strQuizList) as $currQuiz ) {
			if( strlen( $currQuiz ) > 0 ) {
				// Check if he's already had it previously, we don't want to override the "quizTaken"!
				if( !isset( $tempPatQuiz[$currQuiz] ) ) {
					$tempPatQuiz[$currQuiz] = "0";
					array_push( $tempNewQuiz, $currQuiz);
				}
				
				$tempFlagOldQuiz[$currQuiz] = 1;
			}
		}
		
		foreach ( $tempList as $currQuiz ) {
			if( !isset( $tempFlagOldQuiz[$currQuiz['0']] ) ) {
				$this->PatientQuiz->delete( $this->PatientQuiz->getPatientQuizID( $patID, $currQuiz['0'] ) );
			}
		}
		
		foreach ( $tempNewQuiz as $currQuiz ) {
			$this->PatientQuiz->create();
			$this->PatientQuiz->save(
				array( 'patID' => $patID,
						'quizID' => $currQuiz,
						'quizTaken' => '0') 
				);
		}
		
	}
	
	// Save research patient allocation
	private function saveResearchPatients( $strPatientList ) {
		
		// Load original list
		$this->loadModel('ResearchPatients');
		$tempSelectedResearch 	= $this->Session->read('User.currentResearch');
		$tempPatientList 		= $this->ResearchPatients->loadResearchPatients( $tempSelectedResearch ) ;
		
		$tempResearchPatients = array();
		
		$tempAddPatient = array();
		$tempStayPatient = array();
		$tempRemovePatient = array();
		
		foreach( $tempPatientList as $currPatient ) {
			$tempResearchPatients[$currPatient] = $currPatient;
		}
		
		
		foreach( preg_split('/,/', $strPatientList) as $currPatient ) {
			if( strlen( $currPatient ) > 0 ) {
				// Check if he's already had the patient previously
				if( !isset( $tempResearchPatients[$currPatient] ) ) {
					$tempAddPatient[$currPatient] = $currPatient;
				} else {
					$tempStayPatient[$currPatient] = $currPatient;
				}
			}
		}
		
		// Flag ones we had in the past but are no longer with us
		foreach( $tempResearchPatients as $currPatient ) {
			if( !isset($tempStayPatient[$currPatient]) ) {
				$tempRemovePatient[$currPatient] = $currPatient;
			}
		}
		
		// Ones to be removed
		foreach ( $tempRemovePatient as $currPatient ) {
			$this->ResearchPatients->deleteAll(array('ResearchPatients.researchID' => $tempSelectedResearch,
				'ResearchPatients.patID' => $currPatient), false);
		}
		
		// Ones to be added
		foreach ( $tempAddPatient as $currPatient ) {
			$this->ResearchPatients->save( array('researchID' => $tempSelectedResearch,
				'patID' => $currPatient) );
		}
		
	}
	
	// Save research patient allocation
	private function saveResearchAssistant( $strAssistantList ) {
		
		// Load original list
		$this->loadModel('ResearchAssistants');
		$tempSelectedResearch 	= $this->Session->read('User.currentResearch');
		$tempAllAssistants 		= $this->Assistant->loadAllData();
		$tempList 				= $this->ResearchAssistant->loadResearcheAssistants( $tempSelectedResearch );
		
		$tempAddAssistant 		= array();
		$tempKeepAssistant 		= array();
		$tempRemoveAssistant 	= array();
		$tempResearchAssistants	= array();
		
		foreach( $tempList as $currItem ) {
			$tempResearchAssistants[$currItem['ResearchAssistant']['assistantID']] = 1;
		}
		
		foreach( preg_split('/,/', $strAssistantList) as $currAssistant ) {
			if( strlen( $currAssistant ) > 0 ) {
				// Check if he's already had the assistant previously
				if( !isset( $tempResearchAssistants[$currAssistant] ) ) {
					$tempAddAssistant[$currAssistant] = $currAssistant;
				} else {
					$tempKeepAssistant[$currAssistant] = $currAssistant;
				}
			}
		}
		
		foreach( $tempResearchAssistants as $currAssistant => $currAssistantV ) {
			if( !isset( $tempKeepAssistant[$currAssistant] ) ) {
				$tempRemoveAssistant[$currAssistant] = $currAssistant;
			}
		}
		
		// Ones to be removed
		foreach ( $tempRemoveAssistant as $currAssistant ) {
			$this->ResearchAssistant->deleteAll(array('ResearchAssistant.researchID' => $tempSelectedResearch,
				'ResearchAssistant.assistantID' => $currAssistant), false);
		}
		
		// Ones to be added
		foreach ( $tempAddAssistant as $currAssistant ) {
			$this->ResearchAssistant->save( array('researchID' => $tempSelectedResearch,
				'assistantID' => $currAssistant) );
		}
		
	}
}

?>