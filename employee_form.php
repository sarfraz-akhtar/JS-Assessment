<?php
$activeTab = Yii::app()->request->getParam('active-tab', 'basic-info');
$canUpdateEmployee = Yii::app()->user->checkAccess('updateEmployee', array('company' => $this->company)) || Yii::app()->user->checkAccess('updateOwnStoreEmployee', array('store' => $this->store));
$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id' => 'employee-form',
	'enableAjaxValidation' => false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	),
	));
if ($employee->hasErrors()) {
	$errors = $employee->getErrors();
	forEach ($errors as $key => &$_error) {
		$_error = array_unique($_error);
		foreach ($_error as $_e) {
			SPAlert::setErrorAlert($_e);
		}
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<?php
		$tabs = array(BsHtml::tag('li', array('class' => $activeTab == 'basic-info' ? 'active' : ''), BsHtml::link('<i class="fa fa-info-circle"></i> Basic Information', '#basic-info', array('data-toggle' => 'tab', 'class' => 'emp-setting-nav', 'data-tab' => 'basic-info'))));

		if (Yii::app()->user->hasRole('epicoradmin') || Yii::app()->user->checkAccess('updateEmployee', array('company' => $this->company)) || Yii::app()->user->checkAccess('updateOwnStoreEmployee', array('store' => $this->store))) {
			$tabs[] = BsHtml::tag('li', array('class' => $activeTab == 'settings' ? 'active' : ''), BsHtml::link('<i class="fa fa-briefcase"></i> Employment Information', '#settings', array('data-toggle' => 'tab', 'class' => 'emp-setting-nav', 'data-tab' => 'settings')));
		}

		$tabs[] = BsHtml::tag('li', array('class' => $activeTab == 'credentials' ? 'active' : ''), BsHtml::link('<i class="fa fa-unlock-alt"></i> Login Credentials', '#credentials', array('data-toggle' => 'tab', 'class' => 'emp-setting-nav', 'data-tab' => 'credentials')));
		?>

		<div class="panel panel-default">
			<div class="panel-heading employee-update">
				<?php
				if (!$employee->isNewRecord) {
					echo BsHtml::nav(BsHtml::NAV_TYPE_TABS, $tabs, array('id' => 'emp-settings-navbar', 'class' => 'margin-l-0'));
				} else {
					echo '<i class="fa fa-user-plus fa-2x icon-primary"></i>&nbsp;<span><strong>Create Employee</strong></span>';
				}
				?>
			</div>
			<div class="panel-body" id="employee-settings-container">
				<div style="<?php echo!$employee->isNewRecord ? 'padding:0 20px;' : '' ?>" class="tab-content">
					<div class="tab-pane <?php echo $activeTab == 'basic-info' ? 'active' : '' ?>" id="basic-info">
						<div class="row">
							<div class="<?php echo $employee->isNewRecord ? 'col-sm-6 sp-modal sp-ui-modal' : 'col-sm-12 sp-modal sp-ui-modal' ?>">
								<?php if ($employee->isNewRecord): ?>
									<h4 class="page-header no-margin-t"><i class="fa fa-cogs"></i>&nbsp;Settings</h4>
								<?php endif; ?>
								<div class="row">
									<div class="col-sm-6">
										<?php
										echo $form->textFieldControlGroup($employee, 'firstname', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-6">
										<?php
										echo $form->textFieldControlGroup($employee, 'lastname', array(
											'class' => 'input-flat'
										));
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<?php
										echo BsHtml::openTag('div', array(
											'class' => 'form-group has-feedback '
											. ($employee->hasErrors('email') ? 'has-error ' : '')
											. ($employee->email && !$employee->isEmailVerified() ? 'has-warning ' : '')
											. ($employee->email && $employee->isEmailVerified() ? 'has-success' : '')
										));
										echo $form->labelEx($employee, 'email', array('class' => 'control-label'));
										echo BsHtml::openTag('div');
										echo $form->emailField($employee, 'email', array(
											'class' => 'input-flat form-control',
											'data-placement' => 'top',
											'data-content' => 'The employee\'s email address acts as his/her username.  If the employee has no email address and does not want to sign up for one, then you will need to randomly generate a username under the login credentials tab.',
											'data-toggle' => 'popover',
											'data-trigger' => 'hover focus'
										));
										echo BsHtml::openTag('span', array(
											'class' => 'fa form-control-feedback email-verify-feedback '
											. ($employee->hasErrors('email') ? 'fa-times ' : '')
											. ($employee->email && !$employee->isEmailVerified() ? 'fa-exclamation-triangle ' : '')
											. ($employee->email && $employee->isEmailVerified() ? 'fa-check ' : '')
										));
										echo BsHtml::closeTag('span');
										if ($employee->isEmailVerified() || $employee->hasErrors('email')) {
											echo BsHtml::openTag('p', array('class' => 'help-block no-margin-b'));
											echo $employee->email && $employee->isEmailVerified() && !$employee->hasErrors('email') ?
												'Verified!' : $employee->getError('email');
											echo BsHtml::closeTag('p');
										}
										echo BsHtml::closeTag('div');
										echo BsHtml::closeTag('div');
										if ($employee->email && !$employee->hasErrors('email') && !$employee->isEmailVerified()) {
											echo BsHtml::openTag('div', array('class' => 'form-group'));
											echo BsHtml::openTag('div');
											echo BsHtml::link('RESEND VERIFICATION EMAIL', Yii::app()->createUrl('employee/sendemailverification', array('id' => $employee->id)), array(
												'class' => 'btn btn-sm btn-warning'
											));
											echo BsHtml::closeTag('div');
											echo BsHtml::closeTag('div');
										}
										?>
									</div>
									<div class="col-sm-6">
										<?php
										echo $form->textFieldControlGroup($employee, 'employeeId', array(
											'class' => 'input-flat',
											'disabled' => !$canUpdateEmployee
										));
										if (!$employee->hasErrors('employeeId') && $canUpdateEmployee) {
											echo BsHtml::helpBlock('Employee Id field must be unique.');
										}
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<?php
										if ($this->company->isModuleEnabled('timeclock')) {
											echo $form->textFieldControlGroup($employee, 'timeClockPin', array(
												'class' => 'input-flat',
												'disabled' => !$canUpdateEmployee
											));
											if (!$employee->hasErrors('timeClockPin')) {
												echo BsHtml::helpBlock('If left blank, the system will auto-generate a unique 4 digit Time Clock Code after saving.');
											}
										} else {
											echo $form->textFieldControlGroup($employee, 'number', array(
												'class' => 'input-flat',
												'disabled' => !$canUpdateEmployee
											));
										}
										?>
									</div>
									<div class="col-sm-6">
										<?php
										if ($this->company->isModuleEnabled('timeclock')) {
											echo $form->textFieldControlGroup($employee, 'number', array(
												'class' => 'input-flat',
												'disabled' => !$canUpdateEmployee
											));
										} else {
											echo $form->inlineRadioButtonListControlGroup($employee, 'gender', Users::$gender);
										}
										?>
									</div>
								</div>
								<div class="row">
									<div class="<?php print $this->company->isModuleEnabled('timeclock') ? 'col-sm-6' : 'col-sm-12'  ?>">
										<?php
										echo BsHtml::openTag('div');
										if ($employee->photo) {
											echo BsHtml::image(Yii::app()->baseUrl . $employee->photoPath, 'Profile Pic', array('class' => 'img-rounded'));
											?>
											<button id="remove-employee-photo" data-empid="<?php echo $employee->id; ?>" class="btn btn-danger" style="vertical-align: bottom" data-loading-text="Removing...<i class='fa fa-pulse fa-spin'></i>">Remove</button>
											<?php
										}
										echo BsHtml::closeTag('div');
										echo $form->fileFieldControlGroup($employee, 'photo');
										?>
									</div>
									<?php if ($this->company->isModuleEnabled('timeclock')) { ?>
										<div class="col-sm-6">
											<?php
											echo $form->inlineRadioButtonListControlGroup($employee, 'gender', Users::$gender);
											?>
										</div>
									<?php } ?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<?php
										echo $form->textFieldControlGroup($employee, 'birthdate', array(
											'class' => 'input-flat',
											'value' => !empty($employee->birthdate) ? date('m/d/Y', strtotime($employee->birthdate)) : null,
											'placeholder' => 'mm/dd/yyyy',
											'data-hook' => 'datepicker',
											'data-dateformat' => 'mm/dd/yy',
											'data-mindate' => '-75Y',
											'data-maxdate' => '-10Y',
											'data-yearrange' => '-75:+0')
										);
										?>
									</div>
									<div class="col-sm-6">
										<?php
										echo $form->dropdownListControlGroup($employee, 'countryId', Countries::model()->findAllMapped('name'), array(
											'class' => 'input-flat'
										));
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<?php
										echo $form->textFieldControlGroup($employee, 'city', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-6">
										<?php
										echo $form->dropdownListControlGroup($employee, 'state', Countries::$states, array(
											'class' => 'input-flat'
										));
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<?php
										echo $form->numberFieldControlGroup($employee, 'zipcode', array(
											'class' => 'input-flat'
										));
										?>
									</div>
								</div>
								<h3 class="page-header">Contact Details</h3>
								<div class="row">
									<div class="col-sm-4">
										<?php
										echo $form->telFieldControlGroup($employee, 'home_phone', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-4">
										<?php
										if ($employee->cell_phone) {
											$employee->cell_phone = str_replace(array(' ', '-', '(', ')'), '', $employee->cell_phone);
										}
										echo $form->telFieldControlGroup($employee, 'cell_phone', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-4">
										<?php
										echo $form->dropdownListControlGroup(
											$employee, 'cellcarrier', Users::getCellCarrierList(), array(
											'class' => 'input-flat',
											'empty' => "Select a Cell Carrier"
										));
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<?php
										echo $form->textAreaControlGroup($employee, 'address', array(
											'class' => 'form-control input-flat',
											'rows' => 3,
											'empty' => "Select a Cell Carrier"
										));
										?>
									</div>
								</div>
                                                                <h3 class="page-header">Emergency Details</h3>
								<div class="row">
                                                                        <div class="col-sm-3">
										<?php
										echo $form->textFieldControlGroup($employee, 'emergencyContactName', array(
											'class' => 'input-flat'
										));
										?>
									</div>
                                                                        <div class="col-sm-3">
										<?php
										echo $form->textFieldControlGroup($employee, 'emergencyContactRelation', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-3">
										<?php
                                                                                //check coz input mask applied at later stage
										if ($employee->emergencyPrimaryPhone) {
											$employee->emergencyPrimaryPhone = str_replace(array(' ', '-', '(', ')'), '', $employee->emergencyPrimaryPhone);
										}
										echo $form->telFieldControlGroup($employee, 'emergencyPrimaryPhone', array(
											'class' => 'input-flat'
										));
										?>
									</div>
									<div class="col-sm-3">
										<?php
                                                                                //check coz input mask applied at later stage
										if ($employee->emergencySecondaryPhone) {
											$employee->emergencySecondaryPhone = str_replace(array(' ', '-', '(', ')'), '', $employee->emergencySecondaryPhone);
										}
										echo $form->telFieldControlGroup($employee, 'emergencySecondaryPhone', array(
											'class' => 'input-flat'
										));
										?>
									</div>
								</div>
							</div>
							<?php if ($employee->isNewRecord): ?>
								<div class="col-sm-6">
									<h4 class="page-header no-margin-t"><i class="fa fa-tags"></i>&nbsp;Assignments</h4>
									<?php
									if ($scenario === "company") {
										echo BsHtml::dropDownListControlGroup('Users[stores]', '', BsHtml::listData($this->company->stores, 'id', 'name'), array(
											'class' => 'input-flat',
											'label' => 'Stores',
											'multiple' => true,
											'data-hook' => 'select2'
										));
										?>
										<div id="defaultStoreContainer" style="display: none;">
											<?php
											echo BsHtml::dropDownListControlGroup('Users[defaultStoreId]', '', array(), array(
												'class' => 'input-flat',
												'label' => 'Select Default Store'
											));
											?>
										</div>
										<?php
									}
									$positions = array_filter(($scenario === "company" ? $this->company->positions : $this->store->positions), function($_p){
										if ($_p->isAdministrative && !(Yii::app()->user->checkAccess('assignUnassignAdministrativePosition', array('company' => $this->company)) || Yii::app()->user->checkAccess('assignUnassignOwnStoreAdministrativePosition', array('store' => $this->store)))) {
											return false;
										}
										return true;
									});
									echo BsHtml::dropDownListControlGroup('Users[positions]', '', BsHtml::listData($positions, 'id', 'name'), array(
										'class' => 'input-flat',
										'label' => 'Positions',
										'multiple' => true,
										'data-hook' => 'select2'
									));
									?>
									<div id="defaultPositionContainer" style="display: none;">
										<?php
										echo BsHtml::dropDownListControlGroup('Users[defaultPositionId]', '', array(), array(
											'class' => 'input-flat',
											'label' => 'Select Default Position'
										));
										?>
									</div>

								</div>
							<?php endif; ?>
						</div>
					</div>

					<?php
					if (!$employee->isNewRecord):
						if (Yii::app()->user->hasRole('epicoradmin') || Yii::app()->user->checkAccess('updateEmployee', array('company' => $this->company)) || Yii::app()->user->checkAccess('updateOwnStoreEmployee', array('store' => $this->store))) :
							?>
							<div class="tab-pane <?php echo $activeTab == 'settings' ? 'active' : '' ?>" id="settings">
								<div class="padding-bottom margin-t-10 sp-modal sp-ui-modal">
									<div class="row">
										<div class="col-sm-6">
											<h4 class="page-header no-margin-t"><i class="fa fa-info-circle"></i>&nbsp;Information</h4>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->textFieldControlGroup($employee, 'hire_date', array(
														'class' => 'input-flat',
														'value' => !empty($employee->hire_date) ? date('m/d/Y', strtotime($employee->hire_date)) : null,
														'placeholder' => 'mm/dd/yyyy',
														'data-hook' => 'datepicker',
														'data-dateformat' => 'mm/dd/yy',
														'data-mindate' => '-20Y',
														'data-yearrange' => '-20:+10')
													);
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->textFieldControlGroup($employee, 'term_date', array(
														'class' => 'input-flat',
														'value' => !empty($employee->term_date) ? date('m/d/Y', strtotime($employee->term_date)) : null,
														'placeholder' => 'mm/dd/yyyy',
														'data-hook' => 'datepicker',
														'data-dateformat' => 'mm/dd/yy',
														'data-mindate' => '-10Y')
													);
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->textFieldControlGroup($employee, 'restrictedHours', array(
														'class' => 'input-flat'
														)
													);
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->textFieldControlGroup($employee, 'meal_break_length', array(
														'class' => 'input-flat',
														'title' => 'Meal Break Length',
														'data-placement' => 'top',
														'data-content' => 'The length, in minutes, of the meal break.',
														'data-toggle' => 'popover',
														'data-trigger' => 'hover focus')
													);
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->dropdownListControlGroup($employee, 'paid_type', Users::$paidType, array(
														'class' => 'input-flat',
														'title' => 'Paid Type',
														'data-placement' => 'top',
														'data-content' => 'Your choice here determines whether an employee will be paid for Meal Breaks. For example, if the first choice is chosen, then if the employee is marked as Exempt, he/she IS paid for Meal Breaks. If marked as Not Exempt, then he/she is NOT paid for Meal breaks.',
														'data-toggle' => 'popover',
														'data-trigger' => 'hover focus'));
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<?php
													echo $form->dropdownListControlGroup($employee, 'compensation', Users::$compensation, array(
														'help' => 'Unsure how to classify your employees? Click '
														. '<a href="https://www.dol.gov/whd/overtime/fs17a_overview.pdf" target="_blank">here</a>'
														. ' for additional information.',
														'class' => 'input-flat'
													));
													?>
												</div>
											</div>
										</div>
										<div class="col-sm-6 employment-settings">
											<h4 class="page-header no-margin-t"><i class="fa fa-cog"></i>&nbsp;Settings</h4>
											<div class="row margin-b-10">
												<div class="col-xs-12">
													<div class="form-group">
														<div class="col-xs-12">
															<label class="control-label pull-left">
																Is Active
																<div class="pull-right">
																	<?php echo $form->checkBox($employee, 'isActive', array('class' => 'input-flat sp-check')); ?><span></span>
																</div>
															</label>
														</div>
														<div class="col-xs-12">
															<p class="help-block" style="clear: both;"><i class="fa fa-info-circle"></i> Inactive employee will not be able to login.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="row margin-b-10">
												<div class="col-xs-12">
													<div class="form-group">
														<div class="col-xs-12">
															<label class="control-label pull-left">
																Appears on the Schedule
																<div class="pull-right">
																	<?php echo $form->checkBox($employee, 'isScheduled', array('class' => 'input-flat sp-check', 'disabled' => !$employee->isActive)); ?><span></span>
																</div>
															</label>
														</div>
													</div>
												</div>
											</div>
											<?php if ($this->store->isModuleEnabled('timeclock')): ?>
												<div class="row margin-b-10">
													<div class="col-xs-12">
														<div class="form-group">
															<div class="col-xs-12">
																<label class="control-label pull-left">
																	Appears on the Payroll
																	<div class="pull-right">
																		<?php echo $form->checkBox($employee, 'appearsOnPayroll', array('id' => 'appearsOnPayroll', 'class' => 'input-flat sp-check', 'disabled' => !$employee->isActive)); ?><span></span>
																	</div>
																</label>
															</div>
														</div>
													</div>
												</div>
												<div class="row margin-b-10">
													<div class="col-xs-12">
														<div class="form-group">
															<div class="col-xs-12">
																<label class="control-label pull-left">
																	Appears on the Time Clock Portal
																	<div class="pull-right">
																		<?php echo $form->checkBox($employee, 'appearsOnTimeClockPortal', array('id' => 'appearsOnTimeClockPortal', 'class' => 'input-flat sp-check', 'disabled' => !$employee->appearsOnPayroll)); ?><span></span>
																	</div>
																</label>
															</div>
														</div>
													</div>
												</div>
                                                                                                <div class="row margin-b-10">
													<div class="col-xs-12">
														<div class="form-group">
															<div class="col-xs-12">
																<label class="control-label pull-left">
																	Can Clock In from any Location
																	<div class="pull-right">
																		<?php echo $form->checkBox($employee, 'allowTimeClockFromAnyLocation', array('id' => 'allowTimeClockFromAnyLocation', 'class' => 'input-flat sp-check', 'disabled' => !$employee->appearsOnPayroll)); ?><span></span>
																	</div>
																</label>
															</div>
														</div>
													</div>
												</div>
												<div class="row margin-b-10">
													<div class="col-xs-12">
														<div class="form-group">
															<div class="col-xs-12">
																<label class="control-label pull-left">
																	Can clock in/out from his/her dashboard
																	<div class="pull-right">
																		<?php echo $form->checkBox($employee, 'allowTimeClockOnDashboard', array('id' => 'allowTimeClockOnDashboard', 'class' => 'input-flat sp-check', 'disabled' => !$employee->appearsOnPayroll)); ?><span></span>
																	</div>
																</label>
															</div>
														</div>
													</div>
												</div>
												<div class="row margin-b-10">
													<div class="col-xs-12">
														<div class="form-group">
															<div class="col-xs-12">
																<label class="control-label pull-left">
																	Can clock in/out without being scheduled
																	<div class="pull-right">
																		<?php echo $form->checkBox($employee, 'allowTimeClockWithoutBeingScheduled', array('id' => 'allowTimeClockWithoutBeingScheduled', 'class' => 'input-flat sp-check', 'disabled' => !$employee->appearsOnPayroll)); ?><span></span>
																	</div>
																</label>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</div>

								</div>
							</div>
						<?php endif; ?>
						<div class="tab-pane <?php echo $activeTab == 'credentials' ? 'active' : '' ?>" id="credentials">
							<div class="padding-bottom margin-t-10 sp-modal sp-ui-modal">
								<?php if (!$employee->isNewRecord): ?>
									<div class="row">
										<div class="col-sm-6">
											<?php
											if (Yii::app()->authManager->getAuthAssignment('canEditUsernames', Yii::app()->user->id)) {
												echo $form->textFieldControlGroup($employee, 'username', array(
													'class' => 'input-flat',
												));
											} else {
												?>
												<div class="form-group">
													<label class="control-label">
														Username
													</label>
													<div class="sp-control-div">
														<span><?php print $employee->activeUsername; ?></span>
													</div>
												</div>
												<?php
											}
											?>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">
													Password
												</label>
												<div class="sp-control-div">
													<div class="password-generate margin-b-10">
														<a class="btn btn-success btn-sm" target="_blank" href="<?php
														print Yii::app()->createUrl('/employee/regeneratecredentials', array(
																'id' => $employee->id,
																'type' => 'print'
														));
														?>"><i class="fa fa-file-pdf-o"></i> Regenerate Credentials & Print</a>
													</div>
													<div class="password-email">
														<a class="btn btn-primary btn-sm" target="_blank" href="<?php
														print Yii::app()->createUrl('/employee/regeneratecredentials', array(
																'id' => $employee->id,
																'type' => 'email'
														));
														?>"><i class="fa fa-envelope-o"></i> Regenerate Credentials & Email</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="margin-b-10">
	<?php
	if (Yii::app()->user->id === $employee->id || Yii::app()->user->checkAccess('createEmployee', array('company' => $this->company)) || Yii::app()->user->checkAccess('updateEmployee', array('company' => $this->company)) ||
		Yii::app()->user->checkAccess('createOwnStoreEmployee', array('store' => $this->store)) || Yii::app()->user->checkAccess('updateOwnStoreEmployee', array('store' => $this->store))) {
		echo BsHtml::submitButton('SAVE', array('class' => 'btn btn-sm btn-primary'));
	}
	if (!$employee->isNewRecord) {
		$options = array('id' => $employee->id);
		if ($this->entity)
			$options['entityurl'] = $this->entity;
		if ($this->entityid)
			$options['entityid'] = $this->entityid;
		echo BsHtml::link('CHANGE PASSWORD', Yii::app()->createUrl('employee/changepassword', $options), array(
			'class' => 'btn btn-sm btn-warning margin-l-10'
		));

		if (
			(Yii::app()->user->checkAccess('deleteEmployee', array('company' => $this->company)) ||
			Yii::app()->user->checkAccess('deleteOwnStoreEmployee', array('store' => $this->store))) &&
			Yii::app()->user->id !== $employee->id
		) {
			echo BsHtml::link('DELETE', 'javascript:void(0)', array(
				'class' => 'delete-employee btn btn-sm btn-danger margin-l-10',
				'data-entityname' => 'User',
				'data-id' => $employee->id,
				'data-onsuccess' => "window.history.back()",
				'data-messageprefix' => 'User'
			));
		}
	}
	?>
</div>
<?php
$this->endWidget();

Yii::app()->clientScript->registerScript("miniScripts", "$(document).ready(function(){
	$('#Users_cell_phone, #Users_emergencyPrimaryPhone, #Users_emergencySecondaryPhone').inputmask('999-999-9999');
});", CClientScript::POS_END);
