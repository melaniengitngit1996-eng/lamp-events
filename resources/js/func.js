export const func = {
   isMobileView() {
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
         return true
      } else {
         return false
      }
   },
   formatAmount(amount) {
      if (typeof amount === 'string')
         amount = parseFloat(amount)
      return amount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   },
   formatToDateTime(date) {
      var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' };
      var today = new Date(date);

      return today.toLocaleDateString("en-US", options);
   },
}