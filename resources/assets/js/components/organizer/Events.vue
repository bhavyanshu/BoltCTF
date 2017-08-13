<template>
  <div>
    <div v-show="loading" class="text-center">
      <span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="events && events.length">
      <div v-for="ev in events">
        <div class="box box-default">
          <div class="box-header with-border">
            <h4 class="box-title" v-text="ev.event_name"></h4>
            <div class="box-tools pull-right">


            </div>
          </div>
          <div class="box-body">
            <p>{{ev.description}}</p>
            <p v-if="ev.start_time"><span class="fa fa-calendar"></span> <strong>From</strong> {{ev.start_time}} <strong>to</strong> {{ev.end_time}}</p>
            <a :href="'/event/edit/' + ev.ref_guid" type="button" class="btn btn-default" data-widget="edit" data-toggle="tooltip" title="Edit" data-original-title="Edit">
              Edit
              <span class="fa fa-edit"></span>
            </a>
            <a :href="'/event/' + ev.ref_guid + '/categories'" type="button" class="btn btn-info pull-right" title="Category Editor">
              Add Categories
              <span class="fa fa-angle-double-right"></span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <p class="text-muted">
        No events have been added yet! <a href="/event/new/register">Register New Event <span class="fa fa-angle-double-right"></span></a>
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
          axios.spread(function(eventresp, userresp) {
            self.events = eventresp.data.data.event;
            self.currentUser = userresp.data.data.user;
          })
        )

        self.loading = false;
      }
    }
</script>
