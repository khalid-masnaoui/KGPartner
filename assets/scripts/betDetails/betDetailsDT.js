function runAction(actionName, extraData) {
	let clientPageUrl = '*' //TODO: URL SHOULD BE USED INSTEAD OF *
	
	parent.postMessage({ actionName, extraData}, clientPageUrl);
	console.log(`Emitted event " ${actionName} " to ${clientPageUrl}`, {extraData})
}


let tigerCardContainer = document.getElementById("tiger-cards")
let dragonCardContainer = document.getElementById("dragon-cards")

let vendor = document.getElementById("vendor")
let gameType = document.getElementById("game-type")
let tableName = document.getElementById("table-name")

let dragonScore = document.getElementById("dragon-score")
let tigerScore = document.getElementById("tiger-score")

let debitAccount = document.getElementById("debit-account")
let debitBeforeBalance = document.getElementById("debit-before-balance")

let debitAmount = document.getElementById("debit-amount")
let creditBeforeBalance = document.getElementById("credit-before-balance")
let creditAmount = document.getElementById("credit-amount")

let outcomeImage = document.getElementById("outcome-image")

let dragonBonus = document.getElementById("dragon-bonus")
let perfectPair = document.getElementById("perfect-pair")
let dragonPair = document.getElementById("dragon-pair")

let dragon = document.getElementById("dragon")
let tie = document.getElementById("tie")
let suitedTie = document.getElementById("suited-tie")
let tiger = document.getElementById("tiger")


let dragonWinTable = document.getElementById("dragon-win-table")
let tieWinTable = document.getElementById("tie-win-table")
let suitedTieWinTable = document.getElementById("suited-tie-win-table")


let tigerWinTable = document.getElementById("tiger-win-table")


// let tieWinText = document.getElementById("tie-win-text")
// let suitedTieWinText = document.getElementById("suited-tie-win-text")

let dragonChip = document.getElementById("dragon-chip")
let tigerChip = document.getElementById("tiger-chip")
let tieChip = document.getElementById("tie-chip")
let suitedTieChip = document.getElementById("suited-tie-chip")

let startedAt = document.getElementById("started-at")
let settledAt = document.getElementById("settled-at")
let betTime = document.getElementById("bet-time")
let dealerName = document.getElementById("dealer-name")



let dragonPayoutChip = document.getElementById("dragon-payout")
let dragonPayoutText = document.getElementById("dragon-payout-text")

let tigerPayoutChip = document.getElementById("tiger-payout")
let tigerPayoutText = document.getElementById("tiger-payout-text")

let tiePayoutChip = document.getElementById("tie-payout")
let tiePayoutText = document.getElementById("tie-payout-text")

let suitedTiePayoutChip = document.getElementById("suited-tie-payout")
let suitedTiePayoutText = document.getElementById("suited-tie-payout-text")



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
	dealerName.innerHTML = debitData.detail.data.data.dealer.name
	
	startedAt.innerHTML = formatDate(debitData.detail.data.data.startedAt)
	settledAt.innerHTML =  formatDate(debitData.detail.data.data.settledAt)
	betTime.innerHTML =  formatDate(debitData.created_at)
	
	debitAccount.innerHTML = debitData.user_info.account
	debitBeforeBalance.innerHTML = '₩' + formatMoney(debitData.before, 0) 



	let dragonResult = debitData.detail.data.data.result.dragon
	let tigerResult = debitData.detail.data.data.result.tiger
	
	dragonScore.innerHTML = dragonResult.score
	tigerScore.innerHTML = tigerResult.score
	

	let outcomeValue = debitData.detail.data.data.result.outcome.toLowerCase()
	
	outcomeImage.src = getAssetImageUrl(outcomeValue + '-win.png')

	debitAmount.innerHTML = '₩' + formatMoney(debitData.amount, 0) 
	

dragonCardContainer.innerHTML = `<img src="${getCardImageUrl(dragonResult.card)}" class="card" id="dragon-card" alt="${dragonResult.card}">
		` 

	tigerCardContainer.innerHTML = `
		<img src="${getCardImageUrl(tigerResult.card)}" class="card" id="tiger-card" alt="${tigerResult.card}">
		` 
	
	
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
	

	//  0.9
	let noWinChipsOpacity = 0.7
	let winChipsOpacity = 1


	if(foundData.DT_Dragon){
		showElement(dragonChip)
		dragon.innerHTML = formatMoney(foundData.DT_Dragon.stake, 0)

		if(foundData.DT_Dragon.payout > 0){
			dragonPayoutText.innerHTML = formatMoney(foundData.DT_Dragon.payout, 0)

			showElement(dragonWinTable)
			setOpacity(dragonChip, winChipsOpacity)

		} else {
		hideElement(dragonPayoutChip)
			hideElement(dragonWinTable)
			setOpacity(dragonChip, noWinChipsOpacity)
		}
	} else {
		hideElement(dragonChip)
		hideElement(dragonWinTable)
		hideElement(dragonPayoutChip)
}

 


if(foundData.DT_Tiger){
	showElement(tigerChip)
	tiger.innerHTML = formatMoney(foundData.DT_Tiger.stake, 0)


	if(foundData.DT_Tiger.payout > 0){
			tigerPayoutText.innerHTML = formatMoney(foundData.DT_Tiger.payout, 0)

		showElement(tigerWinTable)
		setOpacity(tigerChip, winChipsOpacity)
	} else {
		hideElement(tigerPayoutChip)
		hideElement(tigerWinTable)
		setOpacity(tigerChip, noWinChipsOpacity)
	}
} else {
		hideElement(tigerPayoutChip)
	hideElement(tigerChip)
	hideElement(tigerWinTable)
}


if(foundData.DT_Tie){
	showElement(tieChip)
		// hideElement(tieWinText)
	tie.innerHTML = formatMoney(foundData.DT_Tie.stake, 0)

	if(foundData.DT_Tie.payout > 0){
			tiePayoutText.innerHTML = formatMoney(foundData.DT_Tie.payout, 0)

		showElement(tieWinTable)
		setOpacity(tieChip, winChipsOpacity)

	}  else {
		hideElement(tiePayoutChip)
		hideElement(tieWinTable)
		setOpacity(tieChip, noWinChipsOpacity)
	}
	} else {
		hideElement(tiePayoutChip)
		hideElement(tieChip)
		hideElement(tieWinTable)
		// showElement(tieWinText)
}

 
if(foundData.DT_SuitedTie){
	showElement(suitedTieChip)
	// hideElement(suitedTieWinText)
	
	suitedTie.innerHTML = formatMoney(foundData.DT_SuitedTie.stake, 0)

	if(foundData.DT_SuitedTie.payout > 0){
			suitedTiePayoutText.innerHTML = formatMoney(foundData.DT_SuitedTie.payout, 0)

		showElement(suitedTieWinTable)
		setOpacity(suitedTieChip, winChipsOpacity)
	}  else {
		hideElement(suitedTiePayoutChip)
		hideElement(suitedTieWinTable)
		setOpacity(suitedTieChip, noWinChipsOpacity)
	}
	} else {
		hideElement(suitedTiePayoutChip)
		hideElement(suitedTieChip)
		hideElement(suitedTieWinTable)
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
	return getAssetImageUrl2('cards/' + card + '.png')
}

function getAssetImageUrl2(assetFilePath){
	return '/assets/images/betDetails/' + assetFilePath
}


function getAssetImageUrl(assetFilePath){
	return '/assets/images/betDetails/dragonTiger/' + assetFilePath
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
