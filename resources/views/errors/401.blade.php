@extends('templates.crud_template')

@section('crud_form')
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-4">
					{!!Html::image('css/images/torch-logo-new.png','APO Torch Logo',array('class'=>'img-responsive'))!!}
				</div>
				<div class="col-sm-8">
					<h1 class="ui inverted header">Authorization Error!</h1>
					<p class="inverted">We are sorry - you need to be signed in to access that page.</p>
				</div>
			</div>
		</div>
		<div class="col-sm-2"></div>
	</div>
@endsection
