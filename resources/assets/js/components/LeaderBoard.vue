<template>
  <div>
    <div class="box-header box-border">
      <h2 class="box-title">Leaderboard</h2>
    </div>
    <div v-show="leaders_loading" class="text-center">
      <span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>
      <span class="sr-only">Loading...</span>
    </div>

    <div class="box-body no-padding">
      <table v-if="leaders && leaders.length" class="table">
        <tbody>
          <tr>
            <th># Rank</th>
            <th>Handle</th>
            <th>Points</th>
            <th class="hidden-xs">Last Submission</th>
          </tr>
          <tr v-for="(l, index) in leaders">
            <td v-text="++index"></td>
            <td v-text="l.user.username"></td>
            <td v-text="l.upt_points"></td>
            <td class="hidden-xs" v-text="timeSince(l.updated_at)"></td>
          </tr>
        </tbody>
      </table>
      <div v-else>
        <p class="text-muted text-center">There are currently no stats available</p>
      </div>
    </div>
  </div>
</template>

<script>
import utils from '../mixins.js'
    export default {
      mixins: [utils],
      props: {
        limit: {
          default: 15,
          type: Number
        }
      },
      data: () => ({
        leaders: [],
        leaders_loading: true,
      }),
      created() {
        this.ev_id = window.location.pathname.split('/')[3];
        this.getLeaders();

        setInterval(function () {
          this.getLeaders();
        }.bind(this), 60000);
      },
      methods: {
        getLeaders() {
          axios.get('/api/v1/event/' + this.ev_id + '/leaderboard/' + this.limit)
          .then(response => {
            if(response.status == 204) {
              this.leaders = [];
            }
            else {
              this.leaders = response.data.data.leaders.data;
            }
            this.leaders_loading = false;
          })
        },
      },
    }
</script>
