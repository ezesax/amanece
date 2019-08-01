'use strict'

//USER'S FUNCTIONS

function fillUserTable(page, filter){
	loading();
	
	if(filter != ''){
		var method = 'POST';
		var url = './getUserByFilter';
	}else{
		var method = 'GET';
		var url = './getAllUsers';
	}
	
	$.ajax({
		url: url,
		type: method,
		data:{
			'page': page,
			'_token': $('#laravelToken').val(),
			'filter': filter
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				var elementsCount = 0;
				var headers = '<tr><th>Nombres</th><th>Apellidos</th><th>RUT</th><th>Usuario</th><th>Acciones</th></tr>';
				document.getElementById('userTable').innerHTML = headers;
				for(var i = 0; i < res['users']['data'].length; i++){
					var buttons = '<td class="align-middle"><a style="color:#fff" href="./user-edit?id='+res['users']['data'][i].idUsuario+'" class="btn btn-info grillBtn"><i class="fas fa-pen"></i> Editar</a><button class="btn btn-danger buttonRight grillBtn" id="'+res['users']['data'][i].idUsuario+':'+res['users']['data'][i].NombreUsuario+'" onclick="userDeleteConfirm(this.id)"><i class="fas fa-trash"></i> Eliminar</button></td>';
					var data = '<tr id="tr-'+res['users']['data'][i].idUsuario+'"><td class="align-middle">'+res['users']['data'][i].Nombres+'</td><td class="align-middle">'+res['users']['data'][i].Apellidos+'</td><td class="align-middle">'+res['users']['data'][i].RUT+'</td><td class="align-middle">'+res['users']['data'][i].NombreUsuario+'</td>'+buttons+'</tr>';
					document.getElementById('userTable').innerHTML += data;
					elementsCount++;
				}
				
				document.getElementById('userTable').innerHTML += '<tr id="tr-info"><td></td><td></td><td></td><td></td><td class="align-middle text-right" id="totals">'+elementsCount+' de '+res['users']['total']+'</td></tr>';
				
				document.getElementById('userPagination').innerHTML = "";
				
				if(res['users']['prev_page_url'] == null){
					document.getElementById('userPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}else{
					document.getElementById('userPagination').innerHTML += '<button onclick="fillUserTable('+(res['users']['current_page']-1)+', \''+$('#filterSearch').val()+'\')" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}
				
				document.getElementById('userPagination').innerHTML += '<button type="button" class="btn btn-dark">'+res['users']['current_page']+'</button>';				
				
				if(res['users']['next_page_url'] == null){
					document.getElementById('userPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}else{
					document.getElementById('userPagination').innerHTML += '<button onclick="fillUserTable('+(res['users']['current_page']+1)+', \''+$('#filterSearch').val()+'\')" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function userDeleteConfirm(data){
	var id = data.split(':')[0]; 
	var user = data.split(':')[1];
	$('#userIdToDelete').val(id);
	$('#userDeleteConfirmMessage').html('¿Está seguro que desea eliminar el usuario <strong>'+user+'</strong>?');
	$('#userDeleteConfirm').modal('show');
}

function userDelete(){
	loading();
	
	var id = $('#userIdToDelete').val();
	var tk = $('#csrfToken').val();
	
	$.ajax({
		url:'./deleteUser',
		type:'POST',
		data:{
			'idUsuario': id,
			'_token': tk
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				$('#tr-'+id).remove();
				var totals = ($('#totals').html().split(' de ')[0]-1)+' de '+($('#totals').html().split(' de ')[1]-1);
				$('#totals').html(totals);
				$('#userDeleteConfirm').modal('hide');
				
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function validPassword(pass){
	var correctLength = false;
	var hasMayus = false;
	var hasMinus = false;
	var hasNumber = false;
	
	if(pass.length >= 6 && pass.length <=10)
		correctLength = true;
	
	for(var i = 0; i < pass.length; i++){
		if(!isNaN(pass[i])){
			hasNumber = true;
			break;
		}
	}
	
	for(var i = 0; i < pass.length; i++){
		if(isNaN(pass[i]) && pass[i] == pass[i].toUpperCase()){
			hasMayus = true;
			break;
		}
	}
	
	for(var i = 0; i < pass.length; i++){
		if(isNaN(pass[i]) && pass[i] == pass[i].toLowerCase()){
			hasMinus = true;
			break;
		}
	}
	
	if(correctLength && hasMayus && hasMinus && hasNumber)
		return true;
	
	return false;
}

//BOX'S FUNCTIONS

function fillBoxTable(page){
	loading();
	
	$.ajax({
		url: './getAllBoxes',
		type: 'GET',
		data:{
			'page': page
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				var elementsCount = 0;
				var headers = '<tr><th>Descripción</th><th>Tipo Box</th><th>Valor</th><th>Admite horas bloque</th><th>Acciones</th></tr>';
				document.getElementById('boxTable').innerHTML = headers;
				for(var i = 0; i < res['boxes']['data'].length; i++){
					var buttons = '<td class="align-middle"><a style="color:#fff" href="./box-edit?id='+res['boxes']['data'][i].IdBox+'" class="btn btn-info grillBtn"><i class="fas fa-pen"></i> Editar</a><button class="btn btn-danger buttonRight grillBtn" id="'+res['boxes']['data'][i].IdBox+':'+res['boxes']['data'][i].Descripcion+'" onclick="boxDeleteConfirm(this.id)"><i class="fas fa-trash"></i> Eliminar</button></td>';
					var data = '<tr id="tr-'+res['boxes']['data'][i].IdBox+'"><td class="align-middle">'+res['boxes']['data'][i].Descripcion+'</td><td class="align-middle">'+res['boxes']['data'][i].TipoBox+'</td><td class="align-middle">'+res['boxes']['data'][i].ValorHora+'</td><td class="align-middle">'+res['boxes']['data'][i].AdmiteBloque+'</td>'+buttons+'</tr>';
					document.getElementById('boxTable').innerHTML += data;
					
					elementsCount++;
				}
				
				document.getElementById('boxTable').innerHTML += '<tr id="tr-info"><td></td><td></td><td></td><td></td><td class="align-middle text-right" id="totals">'+elementsCount+' de '+res['boxes']['total']+'</td></tr>';
				
				document.getElementById('boxPagination').innerHTML = "";
				
				if(res['boxes']['prev_page_url'] == null){
					document.getElementById('boxPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}else{
					document.getElementById('boxPagination').innerHTML += '<button onclick="fillBoxTable('+(res['boxes']['current_page']-1)+')" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}
				
				document.getElementById('boxPagination').innerHTML += '<button type="button" class="btn btn-dark">'+res['boxes']['current_page']+'</button>';				
				
				if(res['boxes']['next_page_url'] == null){
					document.getElementById('boxPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}else{
					document.getElementById('boxPagination').innerHTML += '<button onclick="fillBoxTable('+(res['boxes']['current_page']+1)+')" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function boxDeleteConfirm(data){
	var id = data.split(':')[0]; 
	var box = data.split(':')[1];
	$('#boxIdToDelete').val(id);
	$('#boxDeleteConfirmMessage').html('¿Está seguro que desea eliminar el box <strong>'+box+'</strong>?');
	$('#boxDeleteConfirm').modal('show');
}

function boxDelete(){
	loading();
	
	var id = $('#boxIdToDelete').val();
	var tk = $('#csrfToken').val();
	
	$.ajax({
		url:'./deleteBox',
		type:'POST',
		data:{
			'idBox': id,
			'_token': tk
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				$('#tr-'+id).remove();
				var totals = ($('#totals').html().split(' de ')[0]-1)+' de '+($('#totals').html().split(' de ')[1]-1);
				$('#totals').html(totals);
				$('#boxDeleteConfirm').modal('hide');
				
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

//SPECIALITY'S FUNCTIONS

function fillSpecialityTable(page){
	loading();
	
	$.ajax({
		url: './getAllSpecialties',
		type: 'GET',
		data:{
			'page': page
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				var elementsCount = 0;
				var headers = '<tr><th>Especialidad</th><th>Acciones</th></tr>';
				document.getElementById('specialityTable').innerHTML = headers;
				for(var i = 0; i < res['specialities']['data'].length; i++){
					var buttons = '<td class="align-middle"><a style="color:#fff" href="./speciality-edit?id='+res['specialities']['data'][i].IdTipoDoctor+'" class="btn btn-info grillBtn"><i class="fas fa-pen"></i> Editar</a><button class="btn btn-danger buttonRight grillBtn" id="'+res['specialities']['data'][i].IdTipoDoctor+':'+res['specialities']['data'][i].Descripcion+'" onclick="specialityDeleteConfirm(this.id)"><i class="fas fa-trash"></i> Eliminar</button></td>';
					var data = '<tr id="tr-'+res['specialities']['data'][i].IdTipoDoctor+'"><td class="align-middle">'+res['specialities']['data'][i].Descripcion+'</td>'+buttons+'</tr>';
					document.getElementById('specialityTable').innerHTML += data;
					
					elementsCount++;
				}
				
				document.getElementById('specialityTable').innerHTML += '<tr id="tr-info"><td></td><td></td><td></td><td></td><td class="align-middle text-right" id="totals">'+elementsCount+' de '+res['specialities']['total']+'</td></tr>';
				
				document.getElementById('specialityPagination').innerHTML = "";
				
				if(res['specialities']['prev_page_url'] == null){
					document.getElementById('specialityPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}else{
					document.getElementById('specialityPagination').innerHTML += '<button onclick="fillSpecialityTable('+(res['specialities']['current_page']-1)+')" type="button" class="btn btn-dark"><i class="fas fa-arrow-left"></i></button>';
				}
				
				document.getElementById('specialityPagination').innerHTML += '<button type="button" class="btn btn-dark">'+res['specialities']['current_page']+'</button>';				
				
				if(res['specialities']['next_page_url'] == null){
					document.getElementById('specialityPagination').innerHTML += '<button disabled="disabled" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}else{
					document.getElementById('specialityPagination').innerHTML += '<button onclick="fillSpecialityTable('+(res['specialities']['current_page']+1)+')" type="button" class="btn btn-dark"><i class="fas fa-arrow-right"></i></button>';
				}
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function specialityDeleteConfirm(data){
	var id = data.split(':')[0]; 
	var speciality = data.split(':')[1];
	$('#specialityIdToDelete').val(id);
	$('#specialityDeleteConfirmMessage').html('¿Está seguro que desea eliminar la Especialidad <strong>'+speciality+'</strong>?');
	$('#specialityDeleteConfirm').modal('show');
}

function specialityDelete(){
	loading();
	
	var id = $('#specialityIdToDelete').val();
	var tk = $('#csrfToken').val();
	
	$.ajax({
		url:'./deleteSpeciality',
		type:'POST',
		data:{
			'specialityId': id,
			'_token': tk
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				$('#tr-'+id).remove();
				var totals = ($('#totals').html().split(' de ')[0]-1)+' de '+($('#totals').html().split(' de ')[1]-1);
				$('#totals').html(totals);
				$('#specialityDeleteConfirm').modal('hide');
				
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

//RESERVATION FUNCTIONS

function fillReservationSelects(){
	loading();
	
	$.ajax({
		url:'./getAllBoxesWithNoPaginate',
		type:'GET',
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				var yearSelect = document.getElementById('yearSelect');
				var monthSelect = document.getElementById('monthSelect');
				var boxSelect = document.getElementById('boxSelect');
				
				yearSelect.innerHTML = "";
				monthSelect.innerHTML = "";
				boxSelect.innerHTML = "";
				
				var currentYear = new Date().getFullYear();
				var currentMonth = new Date().getMonth()+1;
				var currentDay = new Date().getDate();
				
				if(currentMonth == 0){
					var year = 2;
				}else{
					var year = 1;
				}
				
				for(var i = 0; i < year; i++){
					if(i == 0){
						yearSelect.innerHTML += '<option selected value="'+(currentYear+i)+'">'+(currentYear+i)+'</option>';
					}else{
						yearSelect.innerHTML += '<option value="'+(currentYear+i)+'">'+(currentYear+i)+'</option>';
					}
				}
				
				for(var i = 1; i <= 12; i++){
					if(i == currentMonth){
						monthSelect.innerHTML += '<option selected value="'+i+'">'+monthNames[i-1]+'</option>';
					}else{
						if(currentDay >= 20 && currentDay <= 31 && i == (currentMonth+1)){
							monthSelect.innerHTML += '<option value="'+i+'">'+monthNames[i-1]+'</option>';
						}else{
							monthSelect.innerHTML += '<option disabled value="'+i+'">'+monthNames[i-1]+'</option>';
						}
					}
				}
				
				for(var i = 0; i < res['boxes'].length; i++){
					boxSelect.innerHTML += '<option value="'+res['boxes'][i].IdBox+'">'+res['boxes'][i].Descripcion+'</option>';
				}
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function buildReservationTable(){
	loading();
	
	var year = $('#yearSelect').val();
	var month = $('#monthSelect').val();
	var box = $('#boxSelect').val();
	
	if(month.length == 1)
		month = '0'+month;
	
	$.ajax({
		url:'./getReservationData',
		type:'GET',
		dataType: 'json',
		data: {
			'yearSelect': year,
			'monthSelect': month,
			'boxSelect': box
		},
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				$('#currentBox').val(res['box'].Descripcion);
				
				document.getElementById('reservationTable').innerHTML = "";
				
				for(var i = 0; i < res['daysInMonth']; i++){
					if(i == 0)
						var tr = '<tr><th class="text-center align-middle" id="fixColumn">Bloque Horario</th>';
					
					var day = i+1;
					if(String(day).length == 1)
						day = '0'+day;
					
					var dayOfWeek = dayNames[new Date(year+'-'+month+'-'+day).getDay()];
					tr += '<th class="text-center align-middle">'+(i+1)+'<br>'+dayOfWeek+'</th>';
					
					if(i == (res['daysInMonth'] -1))
						tr += '</tr>';
				}
				
				document.getElementById('reservationTable').innerHTML += tr;
				
				var color = '#eaeaea';
				
				for(var i = 0; i < res['blocks'].length; i++){
					tr = '<tr>';
					for(var j = 0; j < res['daysInMonth']; j++){
						if(j == 0)
							tr += '<td class="align-middle text-center th"><strong>'+res['blocks'][i].Descripcion+'</strong></td>';
							
						var day = (j+1).toString();
						
						if(day.length == 1)
							day = '0'+day;
						
						var id = day+'-'+res['blocks'][i].IdBloqueHorario;
						
						if(dayNames[new Date(year+'-'+month+'-'+day).getDay()] == 'Dom'){
							tr += '<td id="'+id+'" class="alert alert-danger align-middle text-center"></td>';
							
							if(color == '#dddddd'){
								color = '#eaeaea';
							}else{
								color = '#dddddd';
							}
						}else{
							if(isPastDay(year, month, day) && res['userType'] != 1){
								tr += '<td id="'+id+'" style="background-color:'+color+'" class="align-middle text-center" onmouseout="quitDate(this)" onmouseover="showDate(this, \''+day+'/'+month+'/'+year+' '+res['blocks'][i].Descripcion+'\')"></td>';
							}else{
								tr += '<td id="'+id+'" style="background-color:'+color+'" class="align-middle text-center" onmouseout="quitDate(this)" onmouseover="showDate(this, \''+day+'/'+month+'/'+year+' '+res['blocks'][i].Descripcion+'\')" onclick="reserve(\''+year+'-'+month+'-'+day+':'+res['blocks'][i].IdBloqueHorario+':'+res['blocks'][i].Descripcion+'\', this.id, '+res['box'].horaBloque+', '+res['userType']+')"></td>';
							}
						}
					}
					tr += '</tr>';
					document.getElementById('reservationTable').innerHTML += tr;
				}
				
				for(var i = 0; i < res['reservations'].length; i++){
					var id = '#'+res['reservations'][i].Fecha.substring(8,10)+'-'+res['reservations'][i].IdBloqueHorario;
					$(id).css('background-color', '');
					if(res['reservations'][i].IdUsuario == res['userId']){
						$(id).addClass('alert alert-success');
					}else{
						$(id).addClass('alert alert-danger');
					}
					
					if(res['reservations'][i].NombreUsuario.length > 10){
						var userName = res['reservations'][i].NombreUsuario.substring(0, 10);
						userName += '<br>';
						userName += res['reservations'][i].NombreUsuario.substring(10, (res['reservations'][i].NombreUsuario.length));
					}else{
						var userName = res['reservations'][i].NombreUsuario;
					}
					
					$(id).html(userName);
					$(id).prop('onmouseover', '');
					$(id).prop('onmouseout', '');
					$(id).prop('onclick', '');
					$(id).click(function(e){
						checkCancel(e.currentTarget.id);
					});
					$(id).prop('id', res['reservations'][i].IdReserva);
				}
				
				document.getElementById('userSelect').innerHTML = '';
				
				if(res['userType'] == 1){
					for(var i = 0; i < res['user'].length; i++){
						document.getElementById('userSelect').innerHTML += '<option value="'+res['user'][i].idUsuario+'">'+(res['user'][i].Nombres+' '+res['user'][i].Apellidos)+'</option>';
					}
				}else{
					document.getElementById('userSelect').innerHTML += '<option value="'+res['user'][2]+'">'+(res['user'][0]+' '+res['user'][1])+'</option>';
				}
				
				$('#footer').css({'position':'relative', 'bottom':'0px', 'width':'100%'});
				$('#reservationTableContent').css('display', 'block');
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function reserve(data, id, blockHour, userType){
	var year = data.split(':')[0].toString().substring(0,4);
	var month = data.split(':')[0].toString().substring(5,7);
	var day = data.split(':')[0].toString().substring(8,10);
	
	if(!isPastDay(year, month, day) || userType == 1){
		if(blockHour == 0){
			$('#blockHourPanel').css('display', 'none');
		}else{
			$('#blockHourPanel').css('display', 'flex');
		}
		
		$('#freeHour').prop('checked', '');
		$('#periodicHour').prop('checked', '');
		$('#blockHour').prop('checked', '');
		
		if(isMobile()){
			if($('#cell').val() == id){
				fillReservationModal(data.split(':')[0], data.split(':')[2]+':'+data.split(':')[3]);
				$('#modalReservation').modal('show');
			}else{
				$('#cell').val(id);
			}
		}
		
		if(!isMobile()){
			fillReservationModal(data.split(':')[0], data.split(':')[2]+':'+data.split(':')[3]);
			$('#modalReservation').modal('show');
		}
	}else{
		if(isMobile()){
			if($('#cell').val() == id){
				$('#modalMessageTitle').html('Mensaje de sistema');
				$('#modalMessageMessage').html('No puede reservar una fecha pasada');
				$('#modalMessage').modal('show');
			}else{
				$('#cell').val(id);
			}
		}
		
		if(!isMobile()){
			$('#modalMessageTitle').html('Mensaje de sistema');
			$('#modalMessageMessage').html('No puede reservar una fecha pasada');
			$('#modalMessage').modal('show');
		}
	}
}

function checkCancel(reservationId){
	loading();
	
	$.ajax({
		url: './checkCancel',
		type: 'POST',
		data:{
			'reservationId': reservationId,
			'_token': $('#laravelToken').val()
		},
		dataType: 'json',
		success: function(res){
			if(res['type'] == 'error' || res['type'] == 'warning'){
				$('#modalMessageTitle').html('Mensaje de Sistema');
				$('#modalMessageMessage').html(res['message']);
				$('#modalMessage').modal('show');
			}else{
				$('#allHoursPeriod').css('display', 'none');
				
				if(res['user'] == 'NA'){
					var message = 'Va a eliminar la reserva del día '+res['date']+', a las '+res['hour'];
				}else{
					var message = 'Va a eliminar la reserva de '+res['user'].NombreUsuario+', del día '+res['date']+', a las '+res['hour'];
				}
				
				if(res['requireAllConfirmation'] != undefined){
					$('#allHoursPeriod').css('display', 'flex');
				}
				
				$('#idReserve').val(res['reservationId']);
				$('#modalReservationConfirmCancelSubtitle').html(message);
				$('#modalReservationConfirmCancel').modal('show');
			}
			
			loading();
		},
		error: function(er){
			console.log('error');
			loading();
		}
	});
}

function cancelReservation(){
	var reservationId = $('#idReserve').val();
	var token = $('#laravelToken').val();
	
	//delete all
	
	buildReservationTable();
}

function fillReservationModal(date, hour){
	date = date.split('-')[2]+'/'+date.split('-')[1]+'/'+date.split('-')[0];
	var box = $('#currentBox').val();
	$('#boxToReserve').val($('#boxSelect').val());
	$('#modalReservationSubtitle').html('Va a reservar el dia '+date+' a partir de las '+hour+' en el box '+box);
}

function isPastDay(year, month, day){
	var currentYear = new Date().getFullYear();
	var currentMonth = new Date().getMonth()+1;
	var currentDay = new Date().getDate();
	
	if(year < currentYear){
		return true;
	}
	
	if(year == currentYear
		&& month < currentMonth){
			return true;
	}
	
	if(year == currentYear
		&& month == currentMonth
		&& day < currentDay){
			return true;
	}
	
	return false;
}

//OTHER FUNCTIONS
function isMobile(){
	if($(window).width() < 1001)
		return true;
	return false;
}

const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

const dayNames = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];

function loading(){
	if($('#loading').css('display') == 'none'){
		$('#loadingBackGround').css('display', 'block');
		$('#loading').css('display', 'table');
	}else{
		$('#loadingBackGround').css('display', 'none');
		$('#loading').css('display', 'none');
	}
}

function showDate(td, date){
	td.innerHTML = "";
	td.innerHTML = date;
}

function quitDate(td){
	td.innerHTML = "";
}

function scale(num, in_min, in_max, out_min, out_max){
  return (num - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}