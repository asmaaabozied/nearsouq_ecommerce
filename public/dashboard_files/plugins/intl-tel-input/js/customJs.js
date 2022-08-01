(function() {
  var input = document.querySelector("#phone"),
  errorMsg = document.querySelector("#error-msg"),
  validMsg = document.querySelector("#valid-msg");

  // here, the index maps to the error code returned from getValidationError - see readme
  var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

  // initialise plugin
  var iti = window.intlTelInput(input, {
      hiddenInput: "phone",
      nationalMode: true,
      initialCountry: 'ae',
      preferredCountries: ['ae','eg'],
      utilsScript: "../../../../public/dashboard_files/plugins/intl-tel-input/build/js/utils.js?1585994360633"
  });

  var reset = function() {
  input.classList.remove("error");
  errorMsg.innerHTML = "";
  errorMsg.classList.add("hide");
  validMsg.classList.add("hide");
  };

  var handleChange = function() {
      var text = (iti.isValidNumber()) ? "International: " + iti.getNumber() : "Please enter a number below";
      var textNode = document.createTextNode(text);
      output.innerHTML = "";
      output.appendChild(textNode);
  };

  // on blur: validate
  input.addEventListener('blur', function() {
  reset();
  if (input.value.trim()) {
      if (iti.isValidNumber()) {
      validMsg.classList.remove("hide");
      } else {
      input.classList.add("error");
      var errorCode = iti.getValidationError();
      errorMsg.innerHTML = errorMap[errorCode];
      errorMsg.classList.remove("hide");
      }
  }
  });

  // on keyup / change flag: reset
  input.addEventListener('change', reset);
  input.addEventListener('keyup', reset);

  // try {
  //     //  var input = document.querySelector("#phone");
  //     window.intlTelInput(input, {
  //     initialCountry: "auto",
  //     geoIpLookup: function(callback) {
  //         $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
  //         var countryCode = (resp && resp.country) ? resp.country : "";
  //         callback(countryCode);
  //         });
  //     },
  //     utilsScript: "../../../../public/dashboard_files/plugins/intl-tel-input/build/js/utils.js?1590403638580" // just for formatting/placeholders etc
  //     });
  // } catch (error) {
      
  // }
})();