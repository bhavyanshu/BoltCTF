<template>
  <div>

    <div class="col-md-5">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Add Category</h3>
        </div>
        <div class="box-body">
          <form data-parsley-validate v-on:submit.prevent>
            <div class="input-group input-group-sm">
              <input class="form-control" v-on:keyup.enter="saveCategory" data-parsley-errors-container="#errorCategoryForm" required="required" data-parsley-trigger="change" data-parsley-required-message="This is required" v-model="category_name" type="text">
              <span class="input-group-btn">
                <button type="button" v-on:click="saveCategory" class="btn btn-default btn-flat">Save</button>
              </span>
            </div>
            <span id="errorCategoryForm"></span>
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <div v-show="loading" class="text-center">
        <span class="fa fa-cog fa-spin fa-3x fa-fw"></span>
        <span class="sr-only">Loading...</span>
      </div>

      <div v-if="categories && categories.length">
        <h3>
          Categories
          <button v-on:click="orderCategory" v-if="showOrderCat" class="btn btn-xs pull-right margin-top btn-primary">
            Save Order
          </button>
        </h3>
        <draggable :list="categories" class="category-list" :move="checkMoveCategory">
          <div v-for="(cat, index) in categories" class="list-item">
            <div v-bind:data-catindex="cat.ref_guid" class="grabbable">
              <span class="text"><span class="fa fa-bars"></span> {{cat.category_name}}</span>
              <div class="pull-right">
                <a href="#" v-on:click="openEditForm($event, cat)" data-toggle="modal" data-target="#categoryModal" type="button" class="btn btn-box-tool" data-widget="edit" title="Edit" data-original-title="Edit">
                  <span class="fa fa-edit fa-2x"></span>
                </a>
                <a href="#" v-on:click="openChallengeEditor($event, cat)" type="button" class="btn btn-box-tool" data-widget="edit" title="Challenge Editor" data-original-title="Challenge Editor">
                  <span class="fa fa-plus-circle fa-2x"></span>
                </a>
              </div>
            </div>
          </div>
        </draggable>
      </div>
      <div v-else>
        <p class="text-muted">No categories have been added yet!</p>
      </div>

      <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Update Category</h4>
            </div>
            <div class="modal-body">
              <form data-parsley-validate v-on:submit.prevent>
                <div class="input-group col-md-12">
                  <input class="form-control" v-on:keyup.enter="updateCategory" required="required" data-parsley-trigger="change" data-parsley-required-message="This is required" v-model="category_name_up" type="text">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" v-on:click="updateCategory" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div><!-- /.modal -->
    </div>

    <div class="col-md-7" v-show='showChallengeEditor'>
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Category : {{category_name_up}}</h3>
        </div>
        <div class="box-body">
          <h5>Add Challenge</h5>
          <form data-parsley-validate v-on:submit.prevent>
            <div class="input-group input-group-sm">
              <input class="form-control" v-on:keyup.enter="saveChallenge" v-model="challenge_name" data-parsley-errors-container="#errorChallengeForm" required="required" data-parsley-trigger="change" data-parsley-required-message="This is required" type="text">
              <span class="input-group-btn">
                <button type="button" v-on:click="saveChallenge" class="btn btn-default btn-flat">Save</button>
              </span>
            </div>
            <span id="errorChallengeForm"></span>
          </form>

          <div v-if="challenges && challenges.length">
            <hr />
            <h5>
              Challenges
              <button v-on:click="orderChallenge" v-if="showOrderCha" class="btn btn-xs pull-right btn-primary">
                Save Order
              </button>
            </h5>
            <draggable :list="challenges" class="challenge-list" :move="checkMoveChallenge">
              <div v-for="(chlng, index) in challenges" class="list-item">
                <div v-bind:data-chindex="chlng.ref_guid" class="grabbable">
                  <span class="text"><span class="fa fa-bars"></span> {{chlng.challenge_name}}</span>
                  <div class="pull-right">
                    <a href="#" v-on:click="openChallengeEditForm($event, chlng)" data-toggle="modal" data-target="#chlngModal" type="button" class="btn btn-box-tool" data-widget="edit" title="Edit" data-original-title="Edit">
                      <span class="fa fa-edit fa-2x"></span>
                    </a>
                    <a :href="'/challenge/' + chlng.ref_guid" type="button" class="btn btn-box-tool" title="Challenge Editor">
                      <span class="fa fa-chevron-right fa-2x"></span>
                    </a>
                  </div>
                </div>
              </div>
            </draggable>

            <div class="modal fade" id="chlngModal" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Challenge</h4>
                  </div>
                  <form data-parsley-validate v-on:submit.prevent>
                    <div class="modal-body">
                        <div class="input-group col-md-12">
                          <input class="form-control" v-on:keyup.enter="updateChallenge" v-model="challenge_name_up" required="required" data-parsley-trigger="change" data-parsley-required-message="This is required" type="text">
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" v-on:click="updateChallenge" class="btn btn-primary">Save changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div><!-- /.modal -->
          </div>
          <div v-else>
            <p class="text-muted">No challenges have been created yet!</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
    import draggable from 'vuedraggable'
    export default {
      components: {
        draggable,
      },
      data: () => ({
        ev_id : '',
        cat_id : '',
        cha_id: '',
        category_name : '',
        category_name_up : '',
        categories: [],
        loading: true,
        showChallengeEditor: false,
        challenge_name: '',
        challenge_name_up: '',
        challenges: [],
        showOrderCat: false,
        showOrderCha: false,
      }),
      created() {
        this.ev_id = window.location.pathname.split('/')[2];
        this.getCategories();
      },
      methods: {
        getCategories() {
          axios.get('/api/v1/event/' + this.ev_id + '/categories')
          .then(response => {
            this.loading = false;
            this.categories = response.data.data.category
          })
        },

        saveCategory () {
          if(this.category_name.trim().length) {
            this.loading = true;
            axios.post('/api/v1/addcategory', {
              ev_ref_guid: this.ev_id,
              category_name: this.category_name
            })
            .then(response => {
              if(response.data.success == true) {
                this.category_name = '';
                this.getCategories();
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          }
        },

        openEditForm(event, catObj) {
          event.preventDefault();
          this.showChallengeEditor = false;
          this.cat_id = catObj.ref_guid;
          this.category_name_up = catObj.category_name;
        },

        updateCategory () {
          if(this.category_name_up.trim().length) {
            this.loading = true;

            axios.post('/api/v1/category/edit', {
              ev_ref_guid: this.ev_id,
              category_name: this.category_name_up,
              cat_ref_guid: this.cat_id
            })
            .then(response => {
              if(response.data.success == true) {
                this.category_name_up = '';
                this.getCategories();
                $('#categoryModal').modal('hide');
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          }
        },

        openChallengeEditor(event, catObj) {
          event.preventDefault();
          this.cat_id = catObj.ref_guid;
          this.category_name_up = catObj.category_name;
          this.showChallengeEditor = true;
          this.getChallenges();
        },

        getChallenges() {
          axios.get('/api/v1/challenges/' + this.cat_id)
          .then(response => {
            if(response.status == 204) {
              this.challenges = [];
            }
            else {
              this.challenges = response.data.data.challenges;
            }
          })
        },

        saveChallenge () {
          if(this.challenge_name.trim().length) {
            axios.post('/api/v1/addchallenge', {
              ev_ref_guid: this.ev_id,
              cat_ref_guid: this.cat_id,
              challenge_name: this.challenge_name
            })
            .then(response => {
              if(response.data.success == true) {
                this.challenge_name = '';
                this.getChallenges();
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          }
        },

        orderChallenge () {
          var cha_ids = [];

          $(".challenge-list .list-item div.grabbable").map(function() {
            cha_ids.push($(this).attr("data-chindex"));
          });

          axios.post('/api/v1/reorderchallenge', {
            cat_ref_guid: this.cat_id,
            cha_ref_guids: cha_ids
          })
          .then(response => {
            if(response.data.success == true) {
              this.showOrderCha = false;
              this.getChallenges();
            }
          })
          .catch(function (error) {
            console.log(error);
          });
        },

        orderCategory() {
          var cat_ids = [];
          $(".category-list .list-item div.grabbable").map(function(){
            cat_ids.push($(this).attr('data-catindex'));
          });

          axios.post('/api/v1/reordercategory', {
            ev_ref_guid: this.ev_id,
            cat_ref_guids: cat_ids
          })
          .then(response => {
            if(response.data.success == true) {
              this.showOrderCat = false;
              this.getCategories();
            }
          })
        },

        openChallengeEditForm(event, chaObj) {
          event.preventDefault();
          this.cha_id = chaObj.ref_guid;
          this.challenge_name_up = chaObj.challenge_name;
        },

        updateChallenge () {
          if(this.challenge_name_up.trim().length) {
            axios.post('/api/v1/challenge/edit', {
              challenge_name: this.challenge_name_up,
              cha_ref_guid: this.cha_id
            })
            .then(response => {
              if(response.data.success == true) {
                this.challenge_name_up = '';
                this.getChallenges();
                $('#chlngModal').modal('hide');
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          }
        },

        checkMoveChallenge: function(evt){
          this.showOrderCha = true;
        },
        checkMoveCategory: function(evt){
          this.showOrderCat = true;
        },
      }
    }
</script>
