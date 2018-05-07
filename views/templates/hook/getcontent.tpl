<div class="row">
	<div class="col-sm-8">
			{if isset($sv)}
	{if $sv eq 'yes'}
	<div class="alert alert-success">Saved Successfully </div>
	{/if}
	{/if}
		<form method="post" action="">
		<div class="panel panel-default">
			<div class="panel-heading">Configure ReDeal Installation </div>
			<div class="panel-body">
				<div class="form-group">
					 <label>Enable ReDeal</label>
					<input type="checkbox" value="1" {if $pgredealenable ==1} checked {/if}  name="pgredealenable">

				</div>

		  <div class="form-group">
					 <label>Google Tag Manager (e.g GMT-XXXXXXX)</label>
					<input type="textbox"  class="form-control" value="{if $pggtagmanager !=''}{$pggtagmanager|escape:'htmlall':'utf-8'} {/if}" name="pggtagmanager">

				</div>

			</div>
	<div class="panel-footer">
         <button type="submit" class="btn btn-default" name="saveredealtaskconfig">{l s='Save' mod='pgredealtask'} <br> <i class="icon-save"></i> </button>
		</div>
		</div>
	

	</form>

	</div>

</div>