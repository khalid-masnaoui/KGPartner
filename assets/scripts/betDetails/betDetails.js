function runAction(actionName, extraData) {
	let clientPageUrl = '*' //TODO: URL SHOULD BE USED INSTEAD OF *
	
	parent.postMessage({ actionName, extraData}, clientPageUrl);
	console.log(`Emitted event " ${actionName} " to ${clientPageUrl}`, {extraData})
}


let bankerCardContainer = document.getElementById("banker-cards")
let playerCardContainer = document.getElementById("player-cards")

let vendor = document.getElementById("vendor")
let gameType = document.getElementById("game-type")
let tableName = document.getElementById("table-name")

let bankerScore = document.getElementById("banker-score")
let playerScore = document.getElementById("player-score")

let debitAccount = document.getElementById("debit-account")
let debitBeforeBalance = document.getElementById("debit-before-balance")

let debitAmount = document.getElementById("debit-amount")
let creditBeforeBalance = document.getElementById("credit-before-balance")
let creditAmount = document.getElementById("credit-amount")

let outcome = document.getElementById("outcome")
let outcomeImage = document.getElementById("outcome-image")

let playerBonus = document.getElementById("player-bonus")
let perfectPair = document.getElementById("perfect-pair")
let playerPair = document.getElementById("player-pair")

let player = document.getElementById("player")
let tie = document.getElementById("tie")
let banker = document.getElementById("banker")

let bankerBonus = document.getElementById("banker-bonus")
let otherPair = document.getElementById("other-pair")
let bankerPair = document.getElementById("banker-pair")
let superSix = document.getElementById("super-six")
let eitherPair = document.getElementById("either-pair")

let playerWinTable = document.getElementById("player-win-table")
let tieWinTable = document.getElementById("tie-win-table")
let bankerWinTable = document.getElementById("banker-win-table")


let playerChip = document.getElementById("player-chip")
let playerPairChip = document.getElementById("player-pair-chip")

let tieChip = document.getElementById("tie-chip")
let bankerChip = document.getElementById("banker-chip")
let bankerPairChip = document.getElementById("banker-pair-chip")

let playerBonusChip = document.getElementById("player-bonus-chip")
let superSixChip = document.getElementById("super-six-chip")
let eitherPairChip = document.getElementById("either-pair-chip")
let bankerBonusChip = document.getElementById("banker-bonus-chip")

let perfectPairChip = document.getElementById("perfect-pair-chip")



let startedAt = document.getElementById("started-at")
let settledAt = document.getElementById("settled-at")
let betTime = document.getElementById("bet-time")
let dealerName = document.getElementById("dealer-name")

let tiePayoutChip = document.getElementById('tie-payout')
let tiePayoutText = document.getElementById('tie-payout-text')

let playerPayoutChip = document.getElementById('player-payout')
let playerPayoutText = document.getElementById('player-payout-text')

let bankerPayoutChip = document.getElementById('banker-payout')
let bankerPayoutText = document.getElementById('banker-payout-text')

let playerPairPayoutChip = document.getElementById('player-pair-payout')
let playerPairPayoutText = document.getElementById('player-pair-payout-text')

let bankerPairPayoutChip = document.getElementById('banker-pair-payout')
let bankerPairPayoutText = document.getElementById('banker-pair-payout-text')

let playerBonusPayoutChip = document.getElementById("player-bonus-payout")
let playerBonusPayoutText = document.getElementById('player-bonus-payout-text')

let superSixPayoutChip = document.getElementById("super-six-payout")
let superSixPayoutText = document.getElementById('super-six-payout-text')

let eitherPairPayoutChip = document.getElementById("either-pair-payout")
let eitherPairPayoutText = document.getElementById('either-pair-payout-text')

let bankerBonusPayoutChip = document.getElementById("banker-bonus-payout")
let bankerBonusPayoutText = document.getElementById('banker-bonus-payout-text')



let perfectPairPayoutChip = document.getElementById("perfect-pair-payout")
let perfectPairPayoutText = document.getElementById('perfect-pair-payout-text')


let debitData = {}
let creditData = {}

let transactionList = betDetailsData.data.transaction_list

transactionList.forEach((matchData,index) => {
    if (index == 0) {
        creditData = matchData
    } else {
        debitData = matchData
    }
})

handleDebitData()
handleCreditData()

function handleCreditData() {
	creditAmount.innerHTML = '₩' + formatMoney(creditData.amount, 0) 
	creditBeforeBalance.innerHTML = '₩' + formatMoney(creditData.before, 0) 
}


function handleDebitData() {
	vendor.innerHTML = debitData.vendor
	gameType.innerHTML = debitData.game_type

	tableName.innerHTML = debitData.detail.data.data.table.name
	dealerName.innerHTML = debitData.detail.data.data.dealer.name
	
	startedAt.innerHTML = formatDate(debitData.detail.data.data.startedAt)
	settledAt.innerHTML =  formatDate(debitData.detail.data.data.settledAt)
	betTime.innerHTML =  formatDate(debitData.created_at)
	
	debitAccount.innerHTML = debitData.user_info.account
	debitBeforeBalance.innerHTML = '₩' + formatMoney(debitData.before, 0) 
	
	let bankerResult = debitData.detail.data.data.result.banker
	let playerResult = debitData.detail.data.data.result.player
	
	bankerScore.innerHTML = bankerResult.score
	playerScore.innerHTML = playerResult.score
	

	let outcomeValue = debitData.detail.data.data.result.outcome
	let outcomeValueInLowerCase = outcomeValue.toLowerCase()
	
	outcome.innerHTML = outcomeValue
	outcomeImage.src = getAssetImageUrl(outcomeValueInLowerCase + '-win.png')



	debitAmount.innerHTML = '₩' + formatMoney(debitData.amount, 0) 
	
	
	// Player cards
	let playerCardHtml = ''
	let playerCards = playerResult.cards.reverse()
	let playerCardCount = playerCards.length
	let rotate3rdPlayerCard = playerCardCount > 2
	
	for (var index = 0; index < playerCardCount; index++) {
		let extraClasses = ''
		let card = playerCards[index]
		
		// Rotate 1st card if we have more than 2 cards
		if(rotate3rdPlayerCard && index === 0){
			extraClasses += ' rotate-card-270deg '
		}
		
		playerCardHtml += `
		<img src="${getCardImageUrl(card)}" class="card ${extraClasses}" id="player-card${index}" alt="${card}">
		` 
	}
	
	playerCardContainer.innerHTML = playerCardHtml
	
	
	// Bank cards
	let bankerCardHtml = ''
	let bankerCards = bankerResult.cards
	let bankerCardCount = bankerCards.length
	let rotate3rdBankerCard = bankerCardCount > 2
	
	for (var index = 0; index < bankerCardCount; index++) {
		let extraClasses = ''
		let card = bankerCards[index]
		
		// Rotate 3rd card if we have more than 2 cards
		if(rotate3rdBankerCard && index === 2){
			extraClasses += ' rotate-card-90deg '
		}
		
		bankerCardHtml += `
		<img src="${getCardImageUrl(card)}" class="card ${extraClasses}" id="banker-card${index}" alt="${card}">
		` 
	}
	
	bankerCardContainer.innerHTML = bankerCardHtml
	
	let bets = debitData.detail.data.data.participants[0].bets
	updateBetsRelatedData(bets)
}

function updateBetsRelatedData(bets) {
	let foundData = {}
	
	bets.forEach(bet => {
		if(!isEmpty(bet.stake)){
			foundData[bet.code] = bet
		}
	})
	

	let noWinChipsOpacity = 0.82
	let winChipsOpacity = 1


	if(foundData.BAC_Tie){
		showElement(tieChip)
		tie.innerHTML = formatMoney(foundData.BAC_Tie.stake, 0)

		if(foundData.BAC_Tie.payout > 0){
			tiePayoutText.innerHTML = formatMoney(foundData.BAC_Tie.payout, 0)

			showElement(tieWinTable)
			setOpacity(tieChip, winChipsOpacity)
		} else {
			hideElement(tiePayoutChip)
			hideElement(tieWinTable)
			setOpacity(tieChip, noWinChipsOpacity)
		}
	} else {
		hideElement(tiePayoutChip)
		hideElement(tieChip)
		hideElement(tieWinTable)
	}


	if(foundData.BAC_Player){
		showElement(playerChip)
		player.innerHTML = formatMoney(foundData.BAC_Player.stake, 0)

		if(foundData.BAC_Player.payout > 0){
			playerPayoutText.innerHTML = formatMoney(foundData.BAC_Player.payout, 0)

			showElement(playerWinTable)
			setOpacity(playerChip, winChipsOpacity)

		} else {
			hideElement(playerPayoutChip)
			hideElement(playerWinTable)
			setOpacity(playerChip, noWinChipsOpacity)
		}
	} else {
		hideElement(playerPayoutChip)
		hideElement(playerChip)
		hideElement(playerWinTable)
	}


	if(foundData.BAC_Banker){
		showElement(bankerChip)
		banker.innerHTML = formatMoney(foundData.BAC_Banker.stake, 0)

		if(foundData.BAC_Banker.payout > 0){
			bankerPayoutText.innerHTML = formatMoney(foundData.BAC_Banker.payout, 0)

			showElement(bankerWinTable)
			setOpacity(bankerChip, winChipsOpacity)
		}  else {
			hideElement(bankerPayoutChip)
			hideElement(bankerWinTable)
			setOpacity(bankerChip, noWinChipsOpacity)
		}
	} else {
		hideElement(bankerPayoutChip)
		hideElement(bankerChip)
		hideElement(bankerWinTable)
	}


	if(foundData.BAC_PlayerPair){
		showElement(playerPairChip)
		// hideElement(playerPairWinText)

		playerPair.innerHTML = formatMoney(foundData.BAC_PlayerPair.stake, 0)


		if(foundData.BAC_PlayerPair.payout > 0){
			playerPairPayoutText.innerHTML = formatMoney(foundData.BAC_PlayerPair.payout, 0)

			// setOpacity(playerPairChip, winChipsOpacity)
		} else {
			hideElement(playerPairPayoutChip)
			// setOpacity(playerPairChip, noWinChipsOpacity)
		}
	} else {
		hideElement(playerPairPayoutChip)
		hideElement(playerPairChip)
	}

	if(foundData.BAC_BankerPair){
		showElement(bankerPairChip)
		// hideElement(bankerPairWinText)
		
		bankerPair.innerHTML = formatMoney(foundData.BAC_BankerPair.stake, 0)

		if(foundData.BAC_BankerPair.payout > 0){
			bankerPairPayoutText.innerHTML = formatMoney(foundData.BAC_BankerPair.payout, 0)

			// setOpacity(bankerPairChip, winChipsOpacity)
		}  else {
			hideElement(bankerPairPayoutChip)
		// setOpacity(bankerPairChip, noWinChipsOpacity)
	}
} else {
	hideElement(bankerPairPayoutChip)
	hideElement(bankerPairChip)
}



if(foundData.BAC_PlayerBonus){
	showElement(playerBonusChip)
	playerBonus.innerHTML = formatMoney(foundData.BAC_PlayerBonus.stake, 0)

	if(foundData.BAC_PlayerBonus.payout > 0){
		playerBonusPayoutText.innerHTML = formatMoney(foundData.BAC_PlayerBonus.payout, 0)

			// setOpacity(playerBonusChip, winChipsOpacity)
		} else {
			hideElement(playerBonusPayoutChip)
			// setOpacity(playerBonusChip, noWinChipsOpacity)
		}
	} else {
		hideElement(playerBonusPayoutChip)
		hideElement(playerBonusChip)
	}



	if(foundData.BAC_SuperSix){
		showElement(superSixChip)
		superSix.innerHTML = formatMoney(foundData.BAC_SuperSix.stake, 0)

		if(foundData.BAC_SuperSix.payout > 0){
			superSixPayoutText.innerHTML = formatMoney(foundData.BAC_SuperSix.payout, 0)

			// setOpacity(superSixChip, winChipsOpacity)
		} else {
			hideElement(superSixPayoutChip)
			// setOpacity(superSixChip, noWinChipsOpacity)
		}
	} else {
		hideElement(superSixPayoutChip)
		hideElement(superSixChip)
	}



	if(foundData.BAC_EitherPair){
		showElement(eitherPairChip)
		eitherPair.innerHTML = formatMoney(foundData.BAC_EitherPair.stake, 0)

		if(foundData.BAC_EitherPair.payout > 0){
			eitherPairPayoutText.innerHTML = formatMoney(foundData.BAC_EitherPair.payout, 0)

			// setOpacity(eitherPairChip, winChipsOpacity)
		} else {
			hideElement(eitherPairPayoutChip)
			// setOpacity(eitherPairChip, noWinChipsOpacity)
		}
	} else {
		hideElement(eitherPairPayoutChip)
		hideElement(eitherPairChip)
	}



	if(foundData.BAC_BankerBonus){
		showElement(bankerBonusChip)
		bankerBonus.innerHTML = formatMoney(foundData.BAC_BankerBonus.stake, 0)

		if(foundData.BAC_BankerBonus.payout > 0){
			bankerBonusPayoutText.innerHTML = formatMoney(foundData.BAC_BankerBonus.payout, 0)

			// setOpacity(bankerBonusChip, winChipsOpacity)
		} else {
			hideElement(bankerBonusPayoutChip)
			// setOpacity(bankerBonusChip, noWinChipsOpacity)
		}
	} else {
		hideElement(bankerBonusPayoutChip)
		hideElement(bankerBonusChip)
	}



	if(foundData.BAC_PerfectPair){
		showElement(perfectPairChip)
		perfectPair.innerHTML = formatMoney(foundData.BAC_PerfectPair.stake, 0)

		if(foundData.BAC_PerfectPair.payout > 0){
			perfectPairPayoutText.innerHTML = formatMoney(foundData.BAC_PerfectPair.payout, 0)

			// setOpacity(perfectPairChip, winChipsOpacity)
		} else {
			hideElement(perfectPairPayoutChip)
			// setOpacity(perfectPairChip, noWinChipsOpacity)
		}
	} else {
		hideElement(perfectPairPayoutChip)
		hideElement(perfectPairChip)
	}




}

function isEmpty(str) {
	str = String(str).trim()
	return str.length === 0
}

function showElement(domElem) {
	domElem.style.display = 'block'
}

function hideElement(domElem) {
	domElem.style.display = 'none';
}

function hideWinTable() {

}

function setOpacity(domElem, opacity) {
	domElem.style.opacity = opacity;
}

function getCardImageUrl(card){
	card = card.toUpperCase();
	return getAssetImageUrl('cards/' + card + '.png')
}

function getAssetImageUrl(assetFilePath){
	return '/assets/images/betDetails/' + assetFilePath
}


function formatDate(dateStr) {
	let dateParts = dateStr.replace('T', ' ').split('.')
	return dateParts[0] // return date with Z character removed
}


/*
var num = 3543.75873
var formattedMoney = '$' + formatMoney(num, 2, ',', '.'); // "$3,543.76"
*/
function formatMoney(num, decPlaces, thouSeparator, decSeparator) {
	decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces
	
	
	decSeparator = decSeparator == undefined ? "." : decSeparator
	
	thouSeparator = thouSeparator == undefined ? "," : thouSeparator
	
	
	let sign = num < 0 ? "-" : ""
	
	let i = parseInt(num = Math.abs(+num || 0).toFixed(decPlaces)) + ""
	
	let j = i.length 
	j = j > 3 ? j % 3 : 0;
	
	return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(num - i).toFixed(decPlaces).slice(2) : "");
}
