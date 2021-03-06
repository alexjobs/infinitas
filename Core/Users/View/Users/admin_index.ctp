<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	echo $this->Form->create( 'User', array( 'url' => array( 'controller' => 'users', 'action' => 'mass', 'admin' => 'true' ) ) );

		$massActions = $this->Infinitas->massActionButtons(
			array(
				'add',
				'edit',
				'copy',
				'move',
				'toggle',
				'delete'
			)
		);
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first',
						'style' => 'width:25px;'
					),
					$this->Paginator->sort('username'),
					$this->Paginator->sort('email'),
					$this->Paginator->sort('browser') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('operating_system', __d('users', 'OS')) => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('country') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('birthday') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('created') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('modified') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('last_login') => array(
						'style' => 'width:75px;'
					),
					__d('users', 'Active') => array(
						'style' => 'width:50px;'
					)
				)
			);

			foreach ($users as $user) {
				?>
					<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
						<td><?php echo $this->Infinitas->massActionCheckBox($user); ?>&nbsp;</td>
						<td><?php echo $this->Infinitas->adminQuickLink($user['User']); ?>&nbsp;</td>
						<td><?php echo $this->Text->autoLinkEmails($user['User']['email']); ?>&nbsp;</td>
						<td><?php echo $user['User']['browser']; ?>&nbsp;</td>
						<td><?php echo $user['User']['operating_system']; ?>&nbsp;</td>
						<td><?php echo $user['User']['country']; ?>&nbsp;</td>
						<td>
							<?php
								if ($user['User']['birthday']) {
									echo $this->Infinitas->date($user['User']['birthday'], null, false);
								}
								else{
									echo __('Not Set');
								}
							?>&nbsp;
						</td>
						<td><?php echo $this->Infinitas->date($user['User']['created']); ?>&nbsp;</td>
						<td><?php echo $this->Infinitas->date($user['User']['modified']); ?>&nbsp;</td>
						<td><?php echo $this->Infinitas->date($user['User']['last_login']); ?>&nbsp;</td>
						<td><?php echo $this->Infinitas->status($user['User']['active']); ?>&nbsp;</td>
					</tr>
				<?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>