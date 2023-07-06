function runAction(actionName, extraData) {
	let clientPageUrl = '*' //TODO: URL SHOULD BE USED INSTEAD OF *
	
	parent.postMessage({ actionName, extraData}, clientPageUrl);
	console.log(`Emitted event " ${actionName} " to ${clientPageUrl}`, {extraData})
}


let vendor = document.getElementById("vendor")
let gameType = document.getElementById("game-type")
let tableName = document.getElementById("table-name")

let bankerCardContainer = document.getElementById("banker-cards")
let playerCardContainer = document.getElementById("player-cards")

let bankerScore = document.getElementById("banker-score")
let playerScore = document.getElementById("player-score")

let debitAccount = document.getElementById("debit-account")
let debitBeforeBalance = document.getElementById("debit-before-balance")

let debitAmount = document.getElementById("debit-amount")
let creditBeforeBalance = document.getElementById("credit-before-balance")
let creditAmount = document.getElementById("credit-amount")

let outcomeImage = document.getElementById("outcome-image")

let playerBonus = document.getElementById("player-bonus")
let perfectPair = document.getElementById("perfect-pair")
let playerPair = document.getElementById("player-pair")

let player = document.getElementById("player")
let tie = document.getElementById("tie")
let banker = document.getElementById("banker")

let playerWinTable = document.getElementById("player-win-table")
let tieWinTable = document.getElementById("tie-win-table")
let bankerWinTable = document.getElementById("banker-win-table")


let playerChip = document.getElementById("player-chip")
let tieChip = document.getElementById("tie-chip")
let bankerChip = document.getElementById("banker-chip")



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


let firstBankerDice = document.getElementById("firstBankerDice")
let secondBankerDice = document.getElementById("secondBankerDice")

let firstPlayerDice = document.getElementById("firstPlayerDice")
let secondPlayerDice = document.getElementById("secondPlayerDice")





let debitData = {}
let creditData = {}
	
let transactionList = betDetailsData.data.transaction_list

transactionList.forEach(matchData => {
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


	startedAt.innerHTML = formatDate(debitData.detail.data.data.startedAt)
	settledAt.innerHTML =  formatDate(debitData.detail.data.data.settledAt)
	betTime.innerHTML =  formatDate(debitData.created_at)
	
	debitAccount.innerHTML = debitData.user_info.account
	debitBeforeBalance.innerHTML = '₩' + formatMoney(debitData.before, 0) 
	

 

	let bankerResult = debitData.detail.data.data.result.bankerDice
	let playerResult = debitData.detail.data.data.result.playerDice
	
	bankerScore.innerHTML = bankerResult.score
	playerScore.innerHTML = playerResult.score
	

	firstBankerDice.src = getAssetImageUrl('dice/red/'+ bankerResult.first + '.png')
	secondBankerDice.src = getAssetImageUrl('dice/red/' + bankerResult.first + '.png')

	firstPlayerDice.src = getAssetImageUrl('dice/blue/'+ bankerResult.first + '.png')
	secondPlayerDice.src = getAssetImageUrl('dice/blue/' + playerResult.first + '.png')


	let outcomeValue = debitData.detail.data.data.result.outcome
	let outcomeValueInLowerCase = outcomeValue.toLowerCase()
	
	outcomeImage.src = getAssetImageUrl(outcomeValue + '.png')

	debitAmount.innerHTML = '₩' + formatMoney(debitData.amount, 0) 
	
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


	if(foundData.BacBo_Tie){
		showElement(tieChip)
		tie.innerHTML = formatMoney(foundData.BacBo_Tie.stake, 0)

		if(foundData.BacBo_Tie.payout > 0){
			tiePayoutText.innerHTML = formatMoney(foundData.BacBo_Tie.payout, 0)

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


	if(foundData.BacBo_Player){
		showElement(playerChip)
		player.innerHTML = formatMoney(foundData.BacBo_Player.stake, 0)

		if(foundData.BacBo_Player.payout > 0){
			playerPayoutText.innerHTML = formatMoney(foundData.BacBo_Player.payout, 0)

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


	if(foundData.BacBo_Banker){
		showElement(bankerChip)
		banker.innerHTML = formatMoney(foundData.BacBo_Banker.stake, 0)

		if(foundData.BacBo_Banker.payout > 0){
			bankerPayoutText.innerHTML = formatMoney(foundData.BacBo_Banker.payout, 0)

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

function setOpacity(domElem, opacity) {
	domElem.style.opacity = opacity;
}

function getCardImageUrl(card){
	card = card.toUpperCase();
	return getAssetImageUrl2('cards/' + card + '.png')
}

function getAssetImageUrl(assetFilePath){
	return '/assets/images/betDetails/bacBo/' + assetFilePath
}

function getAssetImageUrl2(assetFilePath){
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
