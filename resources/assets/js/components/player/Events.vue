<template>
  <div>
    <div v-show="loading" class="text-center">
      <span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="events && events.length">
      <a v-for="ev in events" :href="'/stadium/event/' + ev.ref_guid" class="row event-item">
        <div class="box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">{{ ev.event_name }}</h4>
          </div>
          <div class="box-body">
            <p>{{ev.description}}</p>
            <p v-if="ev.start_time"><span class="fa fa-calendar"></span> <strong>From</strong> {{ev.start_time}} <strong>to</strong> {{ev.end_time}}</p>
            <a class="btn btn-sm btn-default" :href="'/stadium/event/'+ ev.ref_guid + '/leaderboard'"> View Leaderboard </a>
            <a class="btn btn-sm btn-info pull-right"> View <span class="fa fa-angle-double-right"></span></a>
          </div>
        </div>
      </a>
    </div>
    <div v-else>
      <p class="text-muted">
        No events have been registered yet!
      </p>
    </div>
  </div>
</template>

<script>
import utils from '../../mixins.js';
    export default {
      mixins: [utils],
      data: () => ({
        currentUser: '',
        events: [],
        loading: true
      }),
      created() {
        var self = this

        axios.all([
          axios.get('api/v1/events'),
          axios.get('api/v1/user')
        ])
        .then(
          axios.spread( function(eventresp, userresp) {
            self.events = eventresp.data.data.event;
            self.currentUser = userresp.data.data.user;
          })
        )
        self.loading = false;
      }
    }
</script>
