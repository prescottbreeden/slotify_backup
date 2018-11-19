// global audio variables
let currentPlaylist = [];
let shufflePlaylist = [];
let tempPlaylist = [];
let audioElement;
let isPlaying = false;
let repeat = false;
let shuffle = false;
let temp_songId;
let temp_albumId;
let temp_artistId;
let temp_playlistOrder;
// global UI variables
let mouseDown = false;
let menu_open = false;
let warning_msg = false;
let edit_pw = false;
// global user variables
let userLoggedIn;


// ====================================== //
//			General Functions			  //
// ====================================== //

window.addEventListener("popstate", function() {
	var url = location.href;
	openPagePushState(url);
});

function openPagePushState(url) {
	if(url.indexOf("?") === -1) {
		url = url + "?";
	}
	var encodedURL = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$('.dynamic-content').load(encodedURL);
	$('body').scrollTop(0);
}

function openPage(url) {
	hideOptionsMenu();
	openPagePushState(url);
	history.pushState(null, null, url);
}

function goBack() {
	window.history.back();
}

function goForward() {
	window.history.forward();
}

function logout() {
	$.post("includes/handlers/ajax/logout.php", function() {
		location.reload();
	});
}

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
}

function jumpToPlaylist(playlistId) {
	openPage('playlist.php?id=' + playlistId);
	playFirstSong();
}

function notification(msg) {
	$('.msg-box').show();
	$('.msg-box__btns').hide();
	$('.msg-box__text').text(msg);
	$('.msg-box').css({'color': 'green'});
	$('.msg-box').css({"opacity": "1"});
	setTimeout(function() {
		msgBoxHide($('.msg-box'));
	}, 3000);
}

function warning(msg) {
	$('.warning__text').text(msg);
	$('.warning').css({'color': 'red'});
	$('.warning').css({"opacity": "1"});
	$('.warning__btns').show();
}

function msgBoxHide(box) {
	box.css('opacity', '0');
	setTimeout(() => {
		box.hide();
	}, 1000);

}

function shake() {
	$('.warning').addClass('shake');
	setTimeout(function() {
		$('.warning').removeClass('shake');
	}, 1000);
}

// ====================================== //
//			Edit Profile				  //
// ====================================== //

function updateEmail() {
	let emailValue = $('.userdetails__input[name="email"]').val();
	$.post("includes/handlers/ajax/updateEmail.php", 
		{ email: emailValue, username: userLoggedIn }) 
		.done(function(response) {
			notification(response);
		});
}

function checkOldPassword() {
	let oldPassword = $('.userdetails__input[name="oldPassword"]').val();

	$.post("includes/handlers/ajax/checkOldPassword.php", 
		{ oldPassword: oldPassword, username: userLoggedIn }) 
		.done(function(response) {
			notification(response);
			if(response == 'Success: enter a new password') {
				edit_pw = true;
				$('#new_pw').show();
			}
		});
}

function updatePassword() {
	let p1 = $('.userdetails__input[name="newPassword1"]').val();
	let p2 = $('.userdetails__input[name="newPassword2"]').val();
	let re = /[^A-Za-z0-9]/;

	if(p1 != p2) {
		notification("ERROR: passwords do not match");
		return;
	};

	if(p1.match(re)) {
		notification("ERROR: password must be alphanumeric");
		return;
	};

	if(p1.length > 30 || p1.length < 5) {
		notification("ERROR: password must be between 5 and 30 characters");
		return;
	};	

	if(edit_pw) {
		$.post("includes/handlers/ajax/updatePassword.php", 
			{ pw: p1, username: userLoggedIn }) 
			.done(function(response) {
				location.reload();
				edit_pw = false;
				$('#new_pw').hide();
				notification(response);
			});
	} else {
		notification("ERROR: please confirm your old password again");
	}
}

// ====================================== //
//			DropDown Menus				  //
// ====================================== //

function hideOptionsMenu() {
	let dropDownMenu = $('.dropdown-menu');
	let optionsMenu = $('.options-menu');
	let playlistMenu = $('.playlists-menu');
	let shareMenu = $('.share-menu');
	let userMenu = $('.usermenu');
	let albumMenu = $('.album-menu__item');
	if(userMenu.css('display') != "none") {
		userMenu.css("display", "none");
	}
	if(albumMenu.css('display') != "none") {
		albumMenu.css("display", "none");
	}
	if(dropDownMenu.css('display') != "none") {
		dropDownMenu.css("display", "none");
		optionsMenu.css("display", "none");
		playlistMenu.css("display", "none");
		shareMenu.css("display", "none");
	}
	menu_open = false;
}

function showOptionsMenu(button) {
	let dropDownMenu = $('.dropdown-menu');
	let optionsMenu = $('.options-menu');
	let playlistsMenu = $('.playlists-menu');
	let menuWidth = optionsMenu.width();
	let scrollTop = $(window).scrollTop(); //distance from top of window to document
	let elementOffset = $(button).offset().top; //distance from top of document
	let top = elementOffset - scrollTop;
	let left = $(button).position().left;
	dropDownMenu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline-block" });
	optionsMenu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline-block" });

	// define song details for menu action
	let songId = $(button).prevAll(".songId").val();
	let albumId = $(button).prevAll(".albumId").val();
	let artistId = $(button).prevAll(".albumId").val();
	let playlistOrder = $(button).prevAll(".playlistOrder").val();
	temp_songId = songId;	
	temp_albumId = albumId;
	temp_artistId = artistId;
	temp_playlistOrder = playlistOrder;
	menu_open = true;
}

function showPlaylistsMenu(ele) {
	let playlistsMenu = $('.playlists-menu');
	let menuWidth = playlistsMenu.width();
	let scrollTop = $(window).scrollTop(); //distance from top of window to document
	let elementOffset = $('#open_playlists_menu').offset().top; //distance from top of document
	let top = elementOffset - scrollTop;
	let left = $(ele).offset().left;
	playlistsMenu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline-block" });
}

function showShareMenu(ele) {
	let shareMenu = $('.share-menu');
	let menuWidth = shareMenu.width();
	let scrollTop = $(window).scrollTop(); //distance from top of window to document
	let elementOffset = $('#open_share_menu').offset().top; //distance from top of document
	let top = elementOffset - scrollTop;
	let left = $(ele).offset().left;
	shareMenu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline-block" });
}

function showUserMenu() {
	$('.usermenu').show();
	// this is 100% a hack
	setTimeout(function() {
		menu_open = true;
	}, 100);
}

function showAlbumMenu() {
	$('#album_menu').css({"display": "inline-block"});
	// this is 100% a hack
	setTimeout(function() {
		menu_open = true;
	}, 100);
}

function goToArtist() {
	openPage('artist.php?id=' + temp_artistId);
}

function goToAlbum() {
	openPage('album.php?id=' + temp_albumId);
}

// ====================================== //
//				Saved Music				  //
// ====================================== //

function addAlbumToSaved(albumId) {
	$.post("includes/handlers/ajax/addAlbumToSaved.php", { albumId: albumId, username: userLoggedIn })
		.done(function(response) {
			notification(response)
		});
}

function removeAlbumFromSaved(albumId) {
	$.post("includes/handlers/ajax/deleteAlbumFromSaved.php", { albumId: albumId, username: userLoggedIn })
		.done(function(response) {
			notification(response)
		});
}

function saveCurrentlyPlaying() {
	let current_song_id = audioElement.currentlyPlaying.song_id;

	$.post("includes/handlers/ajax/addSongToSaved.php", { song: current_song_id, username: userLoggedIn })
		.done(function(response) {
			notification(response);
		});
	track_saved();
}

function saveSong() {
	$.post("includes/handlers/ajax/addSongToSaved.php", { song: temp_songId, username: userLoggedIn })
		.done(function(response) {
			notification(response);
		});
	track_saved();
	hideOptionsMenu();
}

function deleteCurrentlyPlaying() {
	let current_song_id = audioElement.currentlyPlaying.song_id;

	$.post("includes/handlers/ajax/deleteSongFromSaved.php", { song: current_song_id, username: userLoggedIn })
		.done(function(response) {
			notification(response);
		});
	track_saved();
}

function removeSongFromSaved(songId=temp_songId) {
	console.log('removing song ' + temp_songId);
	track_saved();
}

function track_saved() {
	let current_song_id = audioElement.currentlyPlaying.song_id;
	$.post("includes/handlers/ajax/checkSongSaved.php", { song: current_song_id, username: userLoggedIn })
		.done(function(response) {
			$('.delete').hide();
			if(response > 0) {
				$('.saved').show();
				$('.not-saved').hide();
			} else {
				$('.saved').hide();
				$('.not-saved').show();
			}
		});
}

function updateLastPlayed() {
	let lp_album = audioElement.currentlyPlaying.album_id; 
	let lp_album_order = audioElement.currentlyPlaying.album_order;
	$.post("includes/handlers/ajax/updateLastPlayed.php", 
		{ lp_album: lp_album, lp_album_order: lp_album_order, username: userLoggedIn })
		.done(function(response) {
		});
}


// ====================================== //
//				Playlists				  //
// ====================================== //

function createPlaylist() {
	var popup = prompt("Please enter the name of your playlist");

	if(popup != null) {
		$.post("includes/handlers/ajax/createPlaylist.php", { pl_name: popup, username: userLoggedIn })
			.done(function(error) {
				openPage("your_music.php");
				// do something when ajax returns
			});
	}
}

function deletePlaylist(pl_id) {
	warning_msg = false;
	$.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: pl_id })
		.done(function(error) {
			openPage("your_music.php");
		});
}

function deleteWarning() {
	warning_msg = true;
	$('.warning').show();
	warning("Are you sure you want to delete this playlist?", true);
}

function deleteCancel() {
	warning_msg = false;
	$('.warning').hide();
}

function addSongToPlaylist(playlistId, songId) {
	$.post("includes/handlers/ajax/checkDuplicateSong.php", { playlistId: playlistId, songId: songId})
		.done(function(numRows) {
			if(numRows == 0) {
			$.post("includes/handlers/ajax/addToPlaylist.php", { playlist_id: playlistId, song_id: songId })
				.done(function(response) {
					hideOptionsMenu();
					notification(response);
				});
			} else {
				notification("This song already exists in this playlist");
			}
	});
}

function removeFromPlaylist(playlistId) {
	$.post("includes/handlers/ajax/removeFromPlaylist.php", { playlist_id: playlistId, song_id: temp_songId, pl_order: temp_playlistOrder })
		.done(function(response) {
			hideOptionsMenu();
			openPage('playlist.php?id=' + playlistId);
			notification(response);
		});
}

function sharePlaylist(playlistId) {
	console.log('generating playlist link');
}


// ====================================== //
//				AUDIO CLASS				  //
// ====================================== //

function Audio() {

	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	// ------ AUDIO EVENT LISTENERS ------ //
	this.audio.addEventListener('ended', function() {
		nextSong();	
	})

	this.audio.addEventListener("canplay", function() {
		var duration = formatTime(this.duration);
		$('.progress-bar__time.progress-bar__time--remaining').text(duration);
	});

	this.audio.addEventListener('timeupdate', function() {
		if(this.duration) {
			updateTimeProgressBar(this);
		}
	});

	this.audio.addEventListener('volumechange', function() {
		updateVolumeProgressBar(this);
	});

	// ------ AUDIO FUNCTIONS ------ //
	this.setTrack = (track) => {
		this.currentlyPlaying = track;
		this.audio.src = 'public/music/' + track.song_path;
	}

	this.play = function() {
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
	}

	this.setTime = (seconds) => {
		this.audio.currentTime = seconds;
	}

}

// ====================================== //
//				PLAYER BAR				  //
// ====================================== //

function formatTime(seconds) {
	var time = Math.round(seconds);
	var minutes = Math.floor(time/60);
	var seconds = time - minutes * 60;
	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ':' + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
	$('.progress-bar__time.progress-bar__time--current').
		text(formatTime(audio.currentTime));
	$('.progress-bar__time.progress-bar__time--remaining').
		text(formatTime(audio.duration - audio.currentTime));

	var progress = audio.currentTime / audio.duration * 100;
	$('#song_progress').css('width', progress + "%");
}

function updateVolumeProgressBar(audio) {
	var volume = audio.volume * 100;
	$('#volume_bar').css('width', volume + "%");
	// initial attempt at having a change in volume automatically remove mute
	if(audioElement.audio.muted) {
		// audioElement.audio.muted = !audioElement.audio.muted;
	}
}

function toggleCurrentlyPlayingStyle(ele) {
	$('.tracks__list--item').removeClass('currently-playing');
	$('.tracks__list--number-sound').hide();
	let val = ele.currentlyPlaying.song_id;
	let currSong = $(`.track_listing[value=${val}]`);
	currSong.parent().addClass('currently-playing');
	drawTracks();
	updateLastPlayed();
}

function drawTracks() {
	let currentTrack = $('.currently-playing');
	if(isPlaying) {
		currentTrack.find('.tracks__list--number-sound').show();
	}
	else {
		currentTrack.find('.tracks__list--number-sound').hide();
	}
}


// ====================================== //
//				EASTER EGGS				  //
// ====================================== //
let currBg = 0;
let eggs = [ 

	{
		background: "megaman",
		tagline: "Always jump through doors into boss rooms"
	},
	{
		background: "zelda",
		tagline: "It's not a candle, it's a flamethrower"
	},
	{
		background: "contra",
		tagline: "\u2191 \u2191 \u2193 \u2193 \u2190 \u2192 \u2190 \u2192 [B] [A] [start]"
	},
	{
		background: "mario",
		tagline: "Heabutting bricks since 1985"
	},
	{
		background: "streetfighter",
		tagline: "Hadooooooouken!"
	},
	{
		background: "tetris",
		tagline: "Oh no, not another square..."
	}
];

function changeBackground() {
	for(let i = 0; i < eggs.length; i++) {
		$('.register').removeClass(eggs[i].background);
	}
	$('.register').addClass(eggs[currBg].background);
	$('.easter-egg--text').text(eggs[currBg].tagline);
	currBg++;
	if(currBg > eggs.length-1) {
		currBg = 0;
	}
}
// ====================================== //
//			EVENT LISTENERS				  //
// ====================================== //

$(document).ready(function() {
	console.log('power overwhelming...');
	changeBackground();

	let isMobile = false; 
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { isMobile = true; }
    if(isMobile) {
		$("#no_mobile").show();
	}

	// register.php behavior
	$('#hideLogin').click(function() {
		$('#loginForm').hide();
		$('#registerForm').show();
	});

	$('#hideRegister').click(function() {
		$('#registerForm').hide();
		$('#loginForm').show();
	});

	$(document).click(function(click) {
		let target = $(click.target);
		if(menu_open) {
			if(!target.hasClass("menu-item") && !target.hasClass("options__button")) {
				hideOptionsMenu();
			}
		} 
		else if(warning_msg) {
			if(!target.is("#warning_cancel") && !target.is("#warning_confirm")) {
				shake();
			}
		}
	});

	$(window).scroll(function() {
		hideOptionsMenu();
	});

	// show additional option menus
	$(document).on('mouseenter', '#open_playlists_menu', function() {
		showPlaylistsMenu($(this));
		$('.share-menu').hide();
	}); 

	$(document).on('mouseenter', '#open_share_menu', function() {
		showShareMenu($(this));
		$('.playlists-menu').hide();
	}); 

	$(document).on('click', '.playlist-item', function() {
		let playlistId = $(this).prevAll(".playlistId").val();
		addSongToPlaylist(playlistId, temp_songId);
	});

	$(document).on('click', '.msg-box', function() {
		if(!warning_msg) {
			msgBoxHide($(this));
		}
	});

	$(document).on('mouseover', '.saved', function() {
		$('.delete').show();
		$('.saved').css({ 'opacity': 0 });
	});
	$(document).on('mouseleave', '.saved', function() {
		$('.delete').hide();
		$('.saved').css({ 'opacity': 1 });
	});
	$(document).on('mouseover', '.delete', function() {
		$('.delete').show();
		$('.saved').css({ 'opacity': 0 });
	});
	$(document).on('mouseleave', '.delete', function() {
		$('.delete').hide();
		$('.saved').css({ 'opacity': 1 });
	});

	$(document).on('mouseover', '.currently-playing', function() {
		let currentTrack = $('.currently-playing');
		currentTrack.find('.tracks__list--number-sound').hide();
		if(isPlaying) {
			currentTrack.find('.tracks__list--number-pause').show();
			currentTrack.find('.tracks__list--number-play').hide();
		}
		else {
			currentTrack.find('.tracks__list--number-pause').hide();
			currentTrack.find('.tracks__list--number-play').show();
		}
	});

	$(document).on('mouseleave', '.currently-playing', function() {
		let currentTrack = $('.currently-playing');
		currentTrack.find('.tracks__list--number-pause').hide();
		currentTrack.find('.tracks__list--number-play').hide();
		if(isPlaying) {
			currentTrack.find('.tracks__list--number-sound').show();
		}
		else {
			currentTrack.find('.tracks__list--number-sound').hide();
		}
	});


});
