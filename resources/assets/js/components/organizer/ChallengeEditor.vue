<template>
  <div>
    <div class="col-md-8">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Challenge : {{ cha_name }}</h3>
        </div>
        <div class="box-body">
          <form data-parsley-validate>
            <div class="input-group input-group-sm">
              <input class="form-control" v-on:keyup.enter="updateChallenge" data-parsley-errors-container="#errorChallengeForm" v-model="cha_name" required="required" data-parsley-trigger="change focusout" data-parsley-required-message="This is required" type="text">
              <span class="input-group-btn">
                <button type="button" v-on:click="updateChallenge" class="btn btn-primary btn-sm btn-flat">Update</button>
              </span>
            </div>
            <span id="errorChallengeForm"></span>
          </form>
        </div>
      </div>

      <div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Add Questions</h3>
          </div>
          <div class="box-body">
            <form data-parsley-validate>
              <div class="form-group">
                <label>Question</label>
                <input class="form-control" placeholder="Write your question here" v-model="question" required="required" data-parsley-trigger="change focusout" data-parsley-required-message="This is required" type="text">
              </div>
              <div class="form-group">
                <label>Question Points</label>
                <input class="form-control" placeholder="Assign points to the question" v-model="question_points" required="required" data-parsley-trigger="change focusout" data-parsley-required-message="This is required" type="text">
              </div>
              <div class="form-group">
                <label>Answers</label>
                <input class="form-control" v-for="answer in answers" v-model="answer.text" type="text" placeholder="Enter correct answer">
              </div>
              <button type="button" v-on:click="addAnswerInput" class="btn btn-default btn-flat"><span class="fa fa-plus"></span> Add More Answers</button>
              <button type="button" v-on:click="saveQA" class="btn btn-primary btn-flat">Save</button>
            </form>
          </div>
        </div>

        <div v-show="loading" class="text-center">
          <span class="fa fa-cog fa-spin fa-3x fa-fw"></span>
          <span class="sr-only">Loading...</span>
        </div>

        <div v-if="qa_list && qa_list.length" class="box">
          <div class="box-body">
            <h4 class="box-title">
              Challenge Questions
              <button v-on:click="reorderQAs" v-if="showOrderQue" class="btn btn-xs pull-right btn-primary">
                Save Order
              </button>
            </h4>
            <draggable :list="qa_list" class="question-list" :move="checkMove">
              <div v-for="(qa, index) in qa_list" class="que-list-item">
                <div v-bind:data-qindex="qa.ref_guid" class="grabbable">
                  <h5 class="text-blue">
                    <span class="fa fa-bars"></span> {{qa.question_text}} - {{ qa.question_points }} Points
                    <a href="#" data-toggle="modal" v-on:click="openQueEditForm($event, qa)" data-target="#queModal" type="button" class="pull-right btn btn-box-tool" data-widget="edit" title="Edit" data-original-title="Edit">
                      <span class="fa fa-edit fa-2x"></span>
                    </a>
                  </h5>
                  <p>Possible Correct Answers:</p>
                  <span v-for="(ans, index) in qa.answerflag">
                    ({{ans.answer_text}})
                  </span>
                </div>
              </div>
            </draggable>

            <div class="modal fade" id="queModal" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Question</h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label>Question</label>
                      <input class="form-control" placeholder="Write your question here" v-model="que_text" type="text">
                    </div>
                    <div class="form-group">
                      <label>Question Points</label>
                      <input class="form-control" placeholder="Assign points to the question" v-model="que_points" type="text">
                    </div>
                    <div class="form-group">
                      <label>Answers</label>
                      <input class="form-control" v-for="ans in temp_ans" v-model="ans.text" type="text" placeholder="Enter correct answer">
                    </div>
                    <button type="button" v-on:click="addTempAnswerInput" class="btn btn-default btn-flat"><span class="fa fa-plus"></span> Add More Answers</button>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" v-on:click="updateQuestion" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <p class="text-muted">
            No questions have been added yet!
          </p>
        </div>

      </div>
    </div>

    <div class="col-md-4">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Attach Files</h3>
        </div>
        <div class="box-body">
            <dropzone id="dzone" ref="dzref" :useCustomDropzoneOptions="true" :dropzoneOptions="dzOptions" url="/api/v1/fileupload" :useFontAwesome="true" :thumbnailHeight="150" :thumbnailWidth="150" v-on:vdropzone-sending="fileUpload" v-on:vdropzone-success="showSuccess">
            </dropzone>
            <div v-if="challengefiles && challengefiles.length">
              <h4>Uploaded Files</h4>
              <p v-for="cf in challengefiles">
                <a :href="'/challenge/file/' + cf.f_token" v-text="cf.f_name"></a>
                <a href="#" v-on:click="deleteFile($event, cf)" class="text-red pull-right"><span class="fa fa-times"></span></a>
              </p>
            </div>
            <div v-show="fetchingFiles" class="text-center">
              <span class="fa fa-cog fa-spin fa-3x fa-fw"></span>
              <span class="sr-only">Loading...</span>
            </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import Dropzone from 'vue2-dropzone'
import draggable from 'vuedraggable'
export default {
  components: {
    Dropzone,
    draggable,
  },
  data: () => ({
    cha_id: '',
    challenge: [],
    cha_name: '',
    challengefiles: [],
    answers: [{
        text: ''
    }],
    question: '',
    question_points: '',
    qa_list: [],
    showOrderQue: false,
    loading: true,
    que_id: '',
    que_text: '',
    que_points: '',
    temp_ans: [],
    dzOptions: {
      addRemoveLinks: true,
      headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
    },
    fetchingFiles: true,
  }),
  created() {
    this.cha_id = window.location.pathname.split('/')[2];
    this.getChallenge();
    this.getQAs();
    this.getChallengeFiles();
    this.loading = false;
  },
  methods: {
    getChallenge() {
      axios.get('/api/v1/challenge/'+this.cha_id)
      .then(response => {
        this.challenge = response.data.data.challenge[0];
        this.cha_name = this.challenge.challenge_name;
      })
    },

    getQAs() {
      axios.get('/api/v1/challenge/' + this.cha_id + '/qa')
      .then(response => {
        if(response.data) {
          this.qa_list = response.data.data.questions;
        }
      })
    },

    getChallengeFiles() {
      this.challengefiles = [];
      axios.get('/api/v1/challenge/' + this.cha_id + '/files')
      .then(response => {
        this.fetchingFiles = false;
        if(response.data) {
          this.challengefiles = response.data.data.challengefiles;
        }
        else {

        }
      })
    },

    updateChallenge() {
      if(this.cha_name.trim().length) {
        axios.post('/api/v1/challenge/edit', {
          challenge_name: this.cha_name,
          cha_ref_guid: this.cha_id
        })
        .then(response => {
          if(response.data.success == true) {
            this.getChallenge();
          }
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    },

    addAnswerInput() {
      this.answers.push({
        text: ''
      });
    },

    addTempAnswerInput() {
      this.temp_ans.push({text: ''});
    },

    saveQA() {
      if(this.question.trim().length && this.question_points.trim().length && this.answers.length) {
        axios.post('/api/v1/qa/save', {
          question: this.question,
          question_points: this.question_points,
          answers: this.answers,
          cha_ref_guid: this.cha_id
        })
        .then(response => {
          if(response.data.success == true) {
            this.question = '';
            this.question_points = '';
            this.answers = [{ text: '' }];
            this.getQAs();
          }
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    },
    checkMove: function(evt){
      this.showOrderQue = true;
    },
    reorderQAs() {
      var que_ids = [];

      $(".question-list .que-list-item div.grabbable").map(function() {
        que_ids.push($(this).attr("data-qindex"));
      });

      axios.post('/api/v1/reorderqa', {
        cha_ref_guid: this.cha_id,
        que_ref_guids: que_ids
      })
      .then(response => {
        if(response.data.success == true) {
          this.showOrderQue = false;
          this.getQAs();
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    openQueEditForm(event, queObj) {
      event.preventDefault();
      this.que_id = queObj.ref_guid;
      this.que_text = queObj.question_text;
      this.que_points = queObj.question_points;
      this.temp_ans = [];
      queObj.answerflag.forEach(af => {
        this.temp_ans.push({text: af.answer_text});
      });
    },
    updateQuestion() {
      axios.post('/api/v1/qa/update', {
        que_ref_guid: this.que_id,
        question: this.que_text,
        question_points: this.que_points,
        answers: this.temp_ans,
        cha_ref_guid: this.cha_id
      })
      .then(response => {
        if(response.data.success == true) {
          $('#queModal').modal('hide');
          this.getQAs();
        }
      })
    },

    fileUpload(file, xhr, formData) {
      formData.append('cha_ref_guid', this.cha_id);
      formData.append("_token", '{{ csrf_token() }}');
    },

    showSuccess(file) {
      this.$refs.dzref.removeAllFiles();
      this.getChallengeFiles();
    },
    deleteFile(event, fileObj) {
      event.preventDefault();
      axios.post('/api/v1/challenge/file/delete',{
        cha_ref_guid: this.cha_id,
        f_token: fileObj.f_token
      })
      .then(response => {
        if(response.data.success == true) {
          this.getChallengeFiles();
        }
      })
    },
  }
}
</script>
