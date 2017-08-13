<template>
  <div>
    <div v-show="queloading" class="text-center">
      <span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>
      <span class="sr-only">Loading...</span>
    </div>

    <div v-if="questions && questions.length" class="box box-primary">
      <div class="box-header">
          <h2 class="box-title">Questions</h2>
      </div>
      <div class="box-body">
        <div v-for="(q, index) in questions" class="row form-inline">
          <div class="col-md-12 question-item">
            <label>{{ index+1 }}. {{ q.question_text }}</label>

            <div v-if="q.submittedflag" class="pull-right">
              <span class="fa fa-check text-green"></span> {{ q.question_points }} Points
            </div>
            <div v-else class="input-group pull-right">
              <div v-if="submitted[index]">
                <span class="fa fa-check text-green"></span> {{ q.question_points }} Points
              </div>
              <div v-else>
                <input type="text" class="form-control" v-model="user_answer[index]" :key="index" placeholder="Write answer here" />
                <div class="input-group-btn">
                  <a v-on:click="submitAnswer($event,q,index)" class="btn btn-primary">Submit</a>
                </div>
                <div class="input-group-addon">
                  <span v-if="wrong_answer[q.question_id]" class="fa fa-times text-red"> Wrong</span> {{ q.question_points }} Points
                </div>
              </div>
            </div>

          </div>
        </div>

        <div v-show="fetchingFiles" class="text-center">
          <span class="fa fa-cog fa-spin fa-3x fa-fw"></span>
          <span class="sr-only">Loading...</span>
        </div>

        <div v-if="challengefiles && challengefiles.length">
          <h4>Files</h4>
          <p v-for="cf in challengefiles">
            <a :href="'/challenge/file/' + cf.f_token" v-text="cf.f_name"></a>
          </p>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
export default {
  data: () => ({
    ev_id: '',
    challenge_id : '',
    queloading: true,
    questions: [],
    user_answer: [''], //wow! Never create empty array when using in v-model
    challengefiles: [],
    wrong_answer: [],
    submitted: [],
    fetchingFiles: true,
  }),
  created() {
    this.ev_id = window.location.pathname.split('/')[3];
    this.challenge_id = window.location.pathname.split('/')[5];
    this.getQuestions();
    this.getChallengeFiles();
  },
  methods: {
    getQuestions() {
      axios.get('/api/v1/stadium/challenge/qas/' + this.challenge_id)
      .then(response => {
        if(response.status == 204) {
          this.questions = [];
        }
        else {
          this.questions = response.data.data.questions;
          this.queloading = false;
        }
      })
    },

    submitAnswer(event,queObj,index) {
      var sbmt_answer = this.user_answer[index];
      this.$set(this.wrong_answer, queObj.question_id, false);
      
      if(sbmt_answer.trim().length) {
        axios.post('/api/v1/stadium/challenge/submit_answer',{
          ev_ref_guid: this.ev_id,
          answer: sbmt_answer,
          que_ref_guid: queObj.ref_guid
        })
        .then(response => {
          if(response.data.data === 1) {
            this.$set(this.wrong_answer, queObj.question_id, false);
            this.$set(this.submitted, index, true);
          }
          else {
            this.$set(this.wrong_answer, queObj.question_id, true);
          }
        })
      }
    },

    getChallengeFiles() {
      this.challengefiles = [];
      axios.get('/api/v1/challenge/' + this.challenge_id + '/files')
      .then(response => {
        this.fetchingFiles = false;
        if(response.data) {
          this.challengefiles = response.data.data.challengefiles;
        }
        else {

        }
      })
    },
  }
}
</script>
