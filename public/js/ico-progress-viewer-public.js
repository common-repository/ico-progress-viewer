(function($) {
    "use strict";

    // TODO: make this work with multiple instances...
    // TODO: change the way settings are managed - instead of storing them
    // in the database, pass them in as arguments to the shortcode.

    // Read the values from the dom
    $(window).load(function() {
        let wrapper = document.querySelector(".ico-progress-viewer");

        if (!wrapper) {
            return;
        }

        let settings = {};

        let attrNames = [
            // General settings
            "dateFormat",

            // Smart contract basic details
            "smartContractAddress",
            "gatewayUrl",
            "abi",

            // Advanced contract method name mappings
            "totalRaised",
            "startTime",
            "endTime",
            "minCap",
            "maxCap"
        ];

        // Try and find the attribute value in the dataset
        attrNames.forEach(function(attr) {
            if (!wrapper.dataset[attr]) {
                throw Error("Missing attribute in wrapper dataset: %s", attr);
            }

            if (attr === "abi") {
                settings[attr] = JSON.parse(wrapper.dataset[attr]);
            } else {
                settings[attr] = wrapper.dataset[attr];
            }
        });

        let intervals = {};
        let intervalDelays = {
            preSaleClock: 1000,
            onSaleClock: 1000,
            onSaleContractData: 14000 // 14 seconds = average block time
        };
        let w3 = new Web3(new Web3.providers.HttpProvider(settings.gatewayUrl));
        let contract = new w3.eth.Contract(settings.abi, settings.smartContractAddress);
        let data;

        function readData() {
            return new Promise(function(resolve, reject) {
                Promise.all([
                    contract.methods[settings.totalRaised].apply().call(),
                    contract.methods[settings.startTime].apply().call(),
                    contract.methods[settings.endTime].apply().call(),
                    contract.methods[settings.maxCap].apply().call(),
                    contract.methods[settings.minCap].apply().call()
                ])
                    .then(function(contractDataResults) {
                        //console.log("resolved data: %o", contractDataResults);
                        data = {
                            address: settings.smartContractAddress,
                            totalRaised: parseFloat(w3.utils.fromWei(contractDataResults[0])).toPrecision(6),
                            startDate: w3.utils.hexToNumber(contractDataResults[1]) * 1000,
                            endDate: w3.utils.hexToNumber(contractDataResults[2]) * 1000,
                            maxCap: parseFloat(w3.utils.fromWei(contractDataResults[3])),
                            minCap: parseFloat(w3.utils.fromWei(contractDataResults[4]))
                        };
                        resolve(data);
                    })
                    .catch(function(resolvedData) {
                        reject("Error calling contract functions:", err);
                    });
            });
        }

        function preSaleInterval(resolvedData) {
            resolvedData = resolvedData || data;

            let now = new Date().getTime();
            let distance = new Date(resolvedData.startDate).getTime() - now;

            if (distance < 0) {
                // cancel the interval when the time reaches zero
                if (intervals.preSaleInterval) {
                    clearInterval(intervals.preSaleInterval);
                }
                if (now > resolvedData.endDate) {
                    updateMode("post-sale");
                    postSaleRendering(resolvedData);
                } else {
                    updateMode("on-sale");
                    setupOnSaleIntervals();
                }
            } else {
                if (!intervals.preSaleInterval) {
                    intervals.preSaleInterval = setInterval(preSaleInterval, intervalDelays.preSaleClock);
                }
                updateMode("pre-sale");
                updateCountdown("pre-sale", resolvedData.startDate, distance);
            }
        }

        function postSaleRendering(data) {
            wrapper.querySelector(".total-raised-amount").innerHTML = data.totalRaised;
        }

        function setupOnSaleIntervals() {
            updateOnSaleData(data);
            timeOnSaleIntervalFunction();
            contractDataOnSaleIntervalFunction();
        }

        function timeOnSaleIntervalFunction() {
            let now = new Date().getTime();
            let distance = new Date(data.endDate).getTime() - now;
            updateCountdown("on-sale", data.endDate, distance);
            if (distance < 0) {
                if (intervals.timeOnSaleIntervalFunction) {
                    clearInterval(intervals.timeOnSaleIntervalFunction);
                }
                updateMode("post-sale");
                postSaleRendering(data);
            } else {
                if (!intervals.timeOnSaleIntervalFunction) {
                    intervals.timeOnSaleIntervalFunction = setInterval(timeOnSaleIntervalFunction, intervalDelays.preSaleClock);
                }
            }
        }

        function contractDataOnSaleIntervalFunction() {
            readData()
                .then(resolvedData => {
                    //console.debug("finished refreshing contract data...", resolvedData);
                    data = resolvedData;
                    updateOnSaleData(resolvedData);
                })
                .catch(function(err) {
                    //console.error("caught an error when trying to refresh contract data: ", err);
                });

            // set the interval, if not already set
            if (!intervals.contractDataOnSaleIntervalFunction) {
                intervals.contractDataOnSaleIntervalFunction = setInterval(
                    contractDataOnSaleIntervalFunction,
                    intervalDelays.onSaleContractData
                );
            }
        }

        function updateOnSaleData(data) {
            // contract address
            wrapper.querySelector(".contract-address").href = `https://etherscan.io/address/${data.address}`;
            wrapper.querySelector(".contract-address").innerHTML = data.address;
            wrapper.querySelector(".contract-address").target = "_blank";

            // additional info
            wrapper.querySelector(".min-cap").innerHTML = `${data.minCap} ETH`;
            wrapper.querySelector(".max-cap").innerHTML = `${data.maxCap} ETH`;

            //progress bar (with a small delay)
            setTimeout(function() {
                let progressBarMax = data.totalRaised > data.minCap ? data.maxCap : data.minCap;
                let percent = Math.min(100, data.totalRaised / progressBarMax * 100);
                wrapper.querySelector(".progress-bar-outer").dataset.progressValue = `${data.totalRaised} ETH`;
                wrapper.querySelector(".progress-bar-inner").style.width = `${percent}%`;
            }, 200);
        }

        function updateMode(mode) {
            let nodeList = wrapper.querySelectorAll(".mode");
            nodeList.forEach(function(element) {
                element.style.display = "none";
            });
            wrapper.querySelector(`.mode.mode-${mode}`).style.display = "block";
        }

        function updateCountdown(mode, abs_date, distance) {
            let d = padNumber(Math.floor(distance / (1000 * 60 * 60 * 24)));
            let h = padNumber(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
            let m = padNumber(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
            let s = padNumber(Math.floor((distance % (1000 * 60)) / 1000));
            let countdownString = `${d}:${h}:${m}:${s}`;
            if (mode === "pre-sale") {
                wrapper.querySelector(".mode-pre-sale .countdown").innerHTML = countdownString;
                wrapper.querySelector(".mode-pre-sale .start-date").innerHTML = moment(abs_date).format(settings.dateFormat);
            } else if (mode === "on-sale") {
                wrapper.querySelector(".mode-on-sale .countdown").innerHTML = countdownString;
                wrapper.querySelector(".mode-on-sale .end-date").innerHTML = moment(abs_date).format(settings.dateFormat);
            }
        }

        function padNumber(num) {
            return num < 10 ? "0" + num : "" + num;
        }

        readData().then(preSaleInterval);
    });
})(jQuery);
