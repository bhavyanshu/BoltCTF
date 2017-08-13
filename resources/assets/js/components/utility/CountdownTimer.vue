<template>
  <div class="box box-info text-center">
    <div v-if="status">
      <div class="box-header">
        <div class="box-title">
          <h4>Time Remaining</h4>
        </div>
      </div>
      <div class="box-body">
        <p>{{days}} Days - {{hours}}H : {{minutes}}m : {{seconds}}s</p>
      </div>
    </div>
    <div v-else>
      <h4>Event has ended!</h4>
    </div>
  </div>
</template>

<script>
import utils from '../../mixins.js'
    export default {
      mixins: [utils],
      props: ['dt'],
      data: () => ({
        days : 0,
        hours : 0,
        minutes : 0,
        seconds : 0,
        status: true
      }),
      created() {
        var self = this;
        this.countdown(self.dt)
        setInterval(function () {
          self.result = this.countdown(self.dt);
        }.bind(this), 1000);
      },
      methods: {
        countdown(dt) {
          // Get date time from provided DT string
          var countDownDate = new Date(dt).getTime();

          // Get todays date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
          this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          this.seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // If the count down is finished, write some text
          if (distance < 0) {
            this.status = false;
            this.redirect();
          }
        },

        redirect() {
          window.location.href = '/dashboard';
        }

      },
    }
</script>
