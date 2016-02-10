
function updateAboutMeForm(profile) {
	if (!profile) return;
	for (var i in profile) {
		var field = $('[name='+i+']');
		if (field && field.length>0) {
			if (profile[i] != null) {
				field.val(profile[i]);
			} else field.val("");
		}
		field = $('.'+i);
		if (field && field.length>0) {
			field.html(profile[i]);
		}
	}
}

function updateMyProfileForm(profile) {
	console.log("MY PROFILE ",profile);
	
	if (profile['birthday']) {
		$('[name=birthday]').datepicker('update',new Date(profile['birthday']));
	}
	
	if (profile['is_active']) {
		field = $('[name=is_active][value='+profile['is_active']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}		
	}
	
	if (profile['orientation_id']) {
		field = $('[name=orientation][value='+profile['orientation_id']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}

	if (profile['relationship_status_id']) {
		field = $('[name=relationship_status][value='+profile['relationship_status_id']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}

	if (profile['gender']) {
		field = $('[name=gender][value='+profile['gender']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}	
}