<template>
  <div class="row">
    <div v-show="catloading" class="text-center">
      <span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="categories && categories.length">
      <div v-for="cat in categories" class="col-md-12">
        <div class="box box-solid">
          <div class="box-header">
            <h2 class="box-title" v-text="cat.category_name"></h2>
          </div>
          <div class="box-body">
            <a class="row" v-for="(cha, index) in cat.challenge" :href="'/stadium/event/' + ev_id + '/challenge/' + cha.ref_guid">
              <div class="challenge-item col-md-12">
                <h4>{{ cha.challenge_name }}</h4>
                <div class="progress-track pull-left">
                  Answered {{ cha.submitted }} / {{ cha.question.length }} Question(s) -
                  Completed {{ progress = ((cha.submitted/cha.question.length)*100).toFixed(2) }} %
                  <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" :style="'width: ' + progress + '%'">
                      <span class="sr-only">{{progress}}% Complete (warning)</span>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    ev_id : '',
    cat_id : '',
    categories: [],
    catloading: true,
  }),
  created() {
    this.ev_id = window.location.pathname.split('/')[3];
    this.getCategories();
  },
  methods: {
    getCategories() {
      axios.get('/api/v1/stadium/event/' + this.ev_id + '/categories')
      .then(response => {
        this.catloading = false;
        this.categories = response.data.data.category;

        this.categories.map(function(cat) {
          cat.challenge.forEach(function(cha,i) {
            let submitted_answer = 0;
            cha.question.forEach(function(que,i){
              if(que.submittedflag) {
                submitted_answer = submitted_answer+1;
              }
            })
            cha['submitted'] = submitted_answer;
          });
        })

      })
    },

  }
}
</script>
