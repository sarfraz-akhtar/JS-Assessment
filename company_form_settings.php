<?php

use Carbon\Carbon;

$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
		'id' => 'timeclock-settings-form',
		'enableAjaxValidation' => false
		));
$activeTab = Yii::app()->request->getParam('active-tab', 'configuration');
?>
<div class="row">
	<div class="col-sm-12">
		<ul class="nav nav-tabs timeclock-settings-tabs sp-nav-tabs" role="tablist">
			<li role="presentation" class="<?php print $activeTab === 'configuration' ? 'active' : ''; ?>"><a href="#configuration" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-cogs"></i>&nbsp;Configuration</a></li>
			<li role="presentation"><a href="#alerts" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-bell"></i>&nbsp;Alerts</a></li>
			<li role="presentation"><a href="#settings" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-sign-in"></i>&nbsp;Time Clock Code</a></li>
			<li role="presentation" class="<?php print $activeTab === 'reports' ? 'active' : ''; ?>"><a href="#reports" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-pie-chart"></i>&nbsp;Reports Settings</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane <?php print $activeTab === 'configuration' ? 'active' : ''; ?>" id="configuration">
				<div class="row margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="pull-left control-label">
								Restrict Remote Clocking

							</label>
						</div>
						<div>
							<?php
							echo BsHtml::helpBlock('Enter comma separated IP addresses from which clocking may be accepted.'
									. ' Your current IP address is <strong>' . Yii::app()->request->userHostAddress . '</strong>');
							?>
						</div>
					</div>
					<div class="col-sm-6 pull-right">
						<div class="margin-b-10">
							<?php
							echo $form->checkBox($timeClockSettings, 'isIPRestricted', array(
									'data-toggle' => 'bootstrap-switch',
									'data-size' => 'small'
							));
							?>
						</div>
						<?php
						echo $form->textArea($timeClockSettings, 'IP_allowedPool', array(
								'rows' => 3
						));
						?>
					</div>
				</div>
				<div class="row margin-b-10">
					<label class="col-xs-6 control-label">
						Show Logout Button ( <i class="fa fa-lg fa-sign-out"></i>) on Time Clock Portal
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'showLogoutButton', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_clockInLimit">
								Limit Early Clock In <small>(minutes)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('Limit the ability of an employee to clock in X minutes before his shift.');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								<?php
								echo $form->checkBox($timeClockSettings, 'isClockInLimitEnabled', array(
										'data-toggle' => 'bootstrap-switch',
										'data-size' => 'small'
								));
								?>
							</div>
							<div class="col-sm-8">
								<?php
								echo $form->numberField($timeClockSettings, 'clockInLimit', array(
										'class' => 'input-flat',
										'placeholder' => '',
										'min' => 0
								));
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_mealInLimit">
								Limit Early Clock In from Meal Break <small>(minutes)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('Limit the ability of an employee to meal in before X minutes.');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								<?php
								echo $form->checkBox($timeClockSettings, 'isMealInLimitEnabled', array(
										'data-toggle' => 'bootstrap-switch',
										'data-size' => 'small'
								));
								?>
							</div>
							<div class="col-sm-8">
								<?php
								echo $form->numberField($timeClockSettings, 'mealInLimit', array(
										'class' => 'input-flat',
										'placeholder' => '',
										'min' => 0
								));
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_punchTimeoutHours">
								Limit Punch Timeout <small>(hours)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('A clocked in punch will timeout after X hours.');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<?php
						echo $form->numberField($timeClockSettings, 'punchTimeoutHours', array(
								'class' => 'input-flat',
								'placeholder' => '',
								'min' => 0
						));
						?>
					</div>
				</div>
				<div class="row">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'rounding', TimeClockSettings::$roundingOptions, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					echo BsHtml::helpBlock('&nbsp;How Rounding Works ?', array(
							'class' => 'col-xs-12 fa fa-book',
							'style' => 'cursor:pointer;',
							'onclick' => 'SP.Views.RoundingHelperModal.show()'
					));
					?>
				</div>
				<div class="row">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'payPeriod', TimeClockSettings::$payPeriodOptions, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row">
					<div class="biweekly-ref-date margin-t-10 clearfix" style="display: none;">
						<div class="col-xs-6 pull-right">
							<?php
							echo BsHtml::activeTextFieldControlGroup($timeClockSettings, 'biWeeklyReferenceDate', array(
									'data-hook' => 'datepicker',
									'append' => '<i class="fa fa-calendar"></i>',
									'appendOptions' => array('addOnOptions' => array('id' => 'biWeeklyDateTrigger')),
									'labelOptions' => false,
									'groupOptions' => array('class' => $timeClockSettings->hasErrors('biWeeklyReferenceDate') ? 'has-error' : ''),
									'error' => BsHtml::error($timeClockSettings, 'biWeeklyReferenceDate', array('class' => 'text-danger'))
							));
							?>
						</div>
						<?php
						echo BsHtml::helpBlock('Please provide a reference start date in case of bi-weekly pay period.', array(
								'class' => 'col-xs-6 pull-left'
						));
						?>
					</div>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'payPeriodStartDay', CalendarHelper::weekDays(), array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'payrollProvider', TimeClockSettings::$payrollProviders, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					echo BsHtml::helpBlock('<i class="fa fa-cogs"></i>&nbsp;Configure ADP Payroll Settings', array(
							'style' => ($timeClockSettings->payrollProvider != 'ADPExport' ? 'display:none;' : '') . 'cursor:pointer;',
							'id' => 'adp_modal_trigger',
							'class' => 'pull-right col-xs-6',
							'onclick' => 'SP.Views.ADPModal.show()'
					));
					echo BsHtml::helpBlock('<i class="fa fa-cogs"></i>&nbsp;Configure Paychex Payroll Settings', array(
							'style' => ($timeClockSettings->payrollProvider != 'PaychexAggregateExport' ? 'display:none;' : '') . 'cursor:pointer;',
							'id' => 'paychex_modal_trigger',
							'class' => 'pull-right col-xs-6',
							'onclick' => 'SP.Views.PaychexEarnCodeModal.show()'
					));
					?>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'visibleEmployees', TimeClockSettings::$visibleEmployeeOptions, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'portalLayout', TimeClockSettings::$portalLayoutOptions, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'timeSheetApproval', TimeClockSettings::$timeSheetApprovalOptions, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row margin-t-10">
					<?php
					echo $form->dropDownListControlGroup($timeClockSettings, 'pdfExportLayout', TimeClockSettings::$pdfExportsLayouts, array(
							'class' => 'input-flat',
							'labelOptions' => array('class' => 'col-xs-6'),
							'controlOptions' => array('class' => 'col-xs-6')
					));
					?>
				</div>
				<div class="row margin-t-10">
					<label class="col-xs-6 control-label">
						Clock Paid Breaks
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'clockPaidBreaks', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
					<?php
					echo BsHtml::helpBlock('The punch widget will have an option to clock in/out paid breaks.', array(
							'class' => 'col-sm-6'
					));
					?>
				</div>
				<div class="row margin-t-10">
					<label class="col-xs-6 control-label">
						Clock Meal Breaks
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'clockMealBreaks', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
					<?php
					echo BsHtml::helpBlock('The punch widget will have an option to clock in/out meal breaks.', array(
							'class' => 'col-sm-6'
					));
					?>
				</div>
				<?php if ($this->company->isModuleEnabled('task')) { ?>
					<div class="row">
						<label class="col-xs-6 control-label">
							Show Tasks On Clock In/Out
						</label>
						<div class="col-xs-6 text-left">
							<?php
							echo $form->checkBox($timeClockSettings, 'showTasksOnClock', array(
									'data-toggle' => 'bootstrap-switch',
									'data-size' => 'small'
							));
							?>
						</div>
						<?php
						echo BsHtml::helpBlock('The employee will be presented with assigned tasks on clock in/out.'
								. ' The widget on the dashboard will include a button to view and mark assigned tasks.', array(
								'class' => 'col-xs-6'
						));
						?>
					</div>
				<?php } ?>
				<div class="row">
					<label class="col-xs-6 control-label">
						Show Employee Number on Payroll
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'showEmployeeIdsOnPayroll', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
				</div>
				</br>
				<div class="row">
					<label class="col-xs-6 control-label">
						Allow Photo On Clock In/Out
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'allowEntryWithPhoto', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
				</div>
                                </br>
				<div class="row">
					<label class="col-xs-6 control-label">
						Allow Job Code on Clock In
					</label>
					<div class="col-xs-6 text-left">
						<?php
						echo $form->checkBox($timeClockSettings, 'allowJobCodeOnClockIn', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
				</div>

				<?php
				if (!Yii::app()->user->hasRole('epicoradmin') || (Yii::app()->user->hasRole('epicoradmin') && Yii::app()->user->checkAccess('editTimeClockSettings', array('company' => $company)) && Yii::app()->user->identity->companyId === $this->company->id)) {
					echo BsHtml::submitButton('Save Changes', array('class' => 'btn btn-sm btn-primary'));
				}
				?>
			</div>
			<div role="tabpanel" class="tab-pane" id="alerts">
				<div class="alert alert-warning" role="alert">
					<strong>Notice!</strong>
					Alerts have been moved to the <a href="/company/alerts">Company Alerts</a> section.
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="settings">
				<div class="row">
					<div class="col-xs-6">
						<?php
						foreach ($company->stores as $store):
							echo BsHtml::label($store->name, NULL);
							?>
							<div class="input-group">
								<div class="input-group-addon copyText" style="cursor: pointer">Copy <i class="fa fa-clipboard"></i></div>
								<?php echo $form->textField($store, 'dashboardPin', array('class' => 'input-flat', 'disabled' => 'disabled')); ?>
								<?php
								if (!Yii::app()->user->hasRole('epicoradmin')) {
									?>
									<div class="input-group-addon refreshDashboardPin" style="cursor: pointer" data-storeid="<?php print $store->id; ?>"><i class="fa fa-refresh"></i></div>
									<?php
								}
								?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane <?php print $activeTab === 'reports' ? 'active' : ''; ?>" id="reports">
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_showSalariedEmployees">
								Show Salaried Employees on Reports
							</label>
						</div>
					</div>
					<div class="pull-right col-sm-6">
						<?php
						echo $form->checkBox($timeClockSettings, 'reportSettings[showSalariedEmployees]', array(
								'data-toggle' => 'bootstrap-switch',
								'data-size' => 'small'
						));
						?>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_earlyClockInGraceTime">
								Early Clock In Grace Time <small>(minutes)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('Clock In before X minutes of scheduled time will be considered <strong>Early Clock In</strong>.');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<?php
						echo $form->numberField($timeClockSettings, 'reportSettings[earlyClockInGraceTime]', array(
								'class' => 'input-flat margin-t-10',
								'placeholder' => '',
								'min' => 1
						));
						?>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_lateClockInGraceTime">
								Late Clock In Grace Time <small>(minutes)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('Clock In after X minutes of scheduled time will be considered <strong>Late Clock In</strong>.');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<?php
						echo $form->numberField($timeClockSettings, 'reportSettings[lateClockInGraceTime]', array(
								'class' => 'input-flat margin-t-10',
								'placeholder' => '',
								'min' => 0
						));
						?>
					</div>
				</div>
				<div class="row clearfix margin-b-10">
					<div class="col-sm-6">
						<div class="clearfix">
							<label class="control-label pull-left" for="TimeClockSettings_earlyClockOutGraceTime">
								Early Clock Out Grace Time <small>(minutes)</small>
							</label>
						</div>
						<?php
						echo BsHtml::helpBlock('Clock Out before X minutes of scheduled time will be considered <strong>Early Clock Out</strong>');
						?>
					</div>
					<div class="pull-right col-sm-6">
						<?php
						echo $form->numberField($timeClockSettings, 'reportSettings[earlyClockOutGraceTime]', array(
								'class' => 'input-flat margin-t-10',
								'placeholder' => '',
								'min' => 0
						));
						?>
					</div>
				</div>
				<?php
				if (!Yii::app()->user->hasRole('epicoradmin') || (Yii::app()->user->hasRole('epicoradmin') && Yii::app()->user->checkAccess('editTimeClockSettings', array('company' => $company)) && Yii::app()->user->identity->companyId === $this->company->id)) {
					echo BsHtml::submitButton('Save Changes', array('class' => 'btn btn-sm btn-primary', 'name' => 'active-tab', 'value' => 'reports'));
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
$this->endWidget();
?>

<script type="text/sp-template" id="template-howroundingworks">
	<ul>
	<li>
	<strong>None</strong> -- Punch duration is based on the actual time clocked by the employee.
	</li>
	<li>
	<strong>15 Minute</strong> -- Round and calculate punches from the nearest quarter of an hour,
	with split occurring in the middle of each quarter hour.<br>
	<strong>Example :</strong> A punch at 7:52a would calculate as 7:45a.<br>
	A punch at 7:53a would calculate as 8:00a.
	</li>
	<li>
	<strong>15 Minute Slant</strong> -- Quarter hour rounding similar to above except the break point
	occurs on the 5th minute or 10th minute depending on whether it is an IN punch or an OUT punch (10/5 split on an in punch,
	5/10 split on an out punch).<br>
	<strong>Examples : </strong>An In punch at 7:49a would calculate as 7:45a.<br>
	An In punch at 7:50a would calculate as 8:00a.<br>
	An Out punch at 5:09p would calculate as 5:00p.<br>
	An Out punch at 5:10p would calculate as 5:15p.
	</li>
	<li>
	<strong>10th Hour</strong> -- Tenths of hour's calculation. This option calculates punches from the
	tenth hour point and advances each six minutes.<br>
	<strong>Example :</strong> An In punch at 7:30a would calculate as 7.5a.<br>
	An Out punch at 4:05p would calculate as 4.0.
	</li>
	</ul>
</script>
