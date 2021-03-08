<template>
  <CTabs tabs class="overflow-y-auto">
    <CTab active>
      <template slot="title">
        <CIcon name="cil-list"/>
      </template>
      <CListGroup tag="div" class="list-group-accent">
        <CListGroupItem tag="div"
                        class="d-flex list-group-item-accent-secondary justify-content-between bg-light font-weight-bold text-muted text-uppercase small">
          Status
          <CIcon v-if="statusData.id" size="lg" name="cil-trash" @click.native="deleteStatus()"
                 class="cursor-pointer text-danger"/>
        </CListGroupItem>
        <CListGroupItem tag="div">
          <InlineEdit v-model="statusData.name" size="lg" :required="true"><h2 v-if="statusData.name">{{
              statusData.name
            }}</h2>
            <h2 class="text-muted" v-else>Set a status name</h2></InlineEdit>
        </CListGroupItem>
        <CListGroupItem tag="div">
          <CRow>
            <CCol class="pb-2">
              <InlineEdit editType="vueselect" :options="colors" :autoscroll="true" :clearable="false"
                          placeholder="Select a colour" v-model="statusData.color" size="lg" :required="true">
                <template v-slot:selected-option="option">
                  <span class="color-swatch small rounded shadow-sm" :class="`fifo-color-${option.label}`"></span>&nbsp;&nbsp;{{
                    option.label | capitalize
                  }}
                </template>
                <template v-slot:option="option">
                  <span class="color-swatch small rounded shadow-sm" :class="`fifo-color-${option.label}`"></span>&nbsp;&nbsp;{{
                    option.label | capitalize
                  }}
                </template>
                <div v-if="statusData.color">
                  <p class="text-muted">
                    <CIcon name="cil-color-palette"/>&nbsp;&nbsp;<span class="color-swatch small rounded shadow-sm"
                                                                       :class="`fifo-color-${statusData.color}`"></span>&nbsp;&nbsp;{{
                      statusData.color | capitalize
                    }}
                  </p>
                </div>
                <div v-else>
                  <p class="text-muted">
                    <CIcon name="cil-color-palette"/>&nbsp;&nbsp;Set a colour for this status
                  </p>
                </div>
              </InlineEdit>
            </CCol>
          </CRow>
          <CRow>
            <CCol>
              <InlineEdit editType="switch" v-model="statusData.completed"
                          label="Mark tasks complete when moved to this status?">
                <p class="text-muted" v-if="statusData.completed">
                  <CIcon name="cil-check-alt"/>&nbsp;&nbsp;Tasks moved to this status will auto-complete
                </p>
                <p class="text-muted" v-else>
                  <CIcon name="cil-check-alt"/>&nbsp;&nbsp;No status changes applied for this status
                </p>
              </InlineEdit>
            </CCol>
          </CRow>
        </CListGroupItem>
        <CListGroupItem v-if="!statusData.id" tag="div">
          <CLoadingButton @click.native="saveStatus()" color="primary" class="mb-2" :loading="saving"
                          :disabled="saving">Save Status
          </CLoadingButton>
        </CListGroupItem>
      </CListGroup>
    </CTab>
    <ReturnConfirm :show.sync="showConfirmation" title="Are you sure?" @cancelClick="cancelDelete()"
                   @okClick="confirmDelete()">
      You're about to delete the <b>{{ statusData.name }}</b> status. Are you sure you want to do this?<br/><br/>This
      action is irreversible, you won't be able to restore this status. Tasks with this status will have their status
      removed and will need to be reallocated to a new status.
    </ReturnConfirm>
  </CTabs>
</template>
<script>

import JobStatusModel from '@/models';

export default {
  name: 'JobStatusSidebar',
  data() {
    return {
      statusData: {},
      statusDataOriginal: {},
      unwatch: null,
      priorityHover: false,
      saving: false,
      showConfirmation: false,
      colors: [
        'white',
        'blue',
        'indigo',
        'purple',
        'pink',
        'red',
        'orange',
        'yellow',
        'green',
        'charcoal',
        'cyan',
        'grey',
        'black'
      ]
    }
  },
  props: {
    status: {
      type: Object,
    },
    job: {
      type: Object,
    }
  },
  watch: {
    status: function (newVal, oldVal) {
      if ('id' in this.status && 'id' in this.statusData && this.status.id !== this.statusData.id) {
        this.unwatch();
        this.setWatchers();
      }
    },
  },
  computed: {},
  methods: {
    setWatchers() {
      let vm = this;
      if (!vm.status && vm.job) {
        //let model = JobStatusModel)
        vm.statusData = vm.$helpers.clone(JobStatusModel)
        vm.statusData.job = vm.job
        vm.statusData.job_id = vm.job.id
      } else {
        vm.statusData = vm.status
        vm.statusDataOriginal = vm.$helpers.clone(vm.status)
        axios.get(`/jobs/${vm.statusData.job.id}/statuses/${vm.statusData.id}`).then(res => {
          vm.statusData = res.data
          vm.statusDataOriginal = vm.$helpers.clone(res.data);
          vm.unwatch = vm.$watch('statusData', function () {
            if (vm.$helpers.hashObject(vm.statusData) !== vm.$helpers.hashObject(vm.statusDataOriginal)) {
              axios.put(`/jobs/${vm.statusData.job.id}/statuses/${vm.statusData.id}`, vm.statusData).then(res => {
                vm.statusData = res.data
                vm.statusDataOriginal = vm.$helpers.clone(res.data);
              });
              vm.$root.$emit('toast', {
                title: 'Status Updated',
                content: `Changes to <b>${vm.statusData.name}</b> have been saved.`,
              });
            }
          }, {deep: true});
        });
      }
    },
    saveStatus() {
      let vm = this;
      vm.saving = true;
      axios.post(`/jobs/${vm.statusData.job.id}/statuses`, vm.statusData)
          .then(res => {
            vm.$root.$emit('aside', '', {});
            vm.$store.commit('set', ['asideShow', false])
            vm.saving = false;
            vm.$root.$emit('toast', {
              title: 'Status Added',
              content: `Changes to <b>${vm.statusData.name}</b> have been saved.`,
            });
          })
          .catch(function (error) {
            vm.$root.$emit('toast', {
              title: 'Unable to save status',
              content: `There was a problem saving <b>${vm.statusData.name}</b>, please try again.`,
              color: 'danger'
            });
          });
    },
    deleteStatus() {
      this.showConfirmation = true
    },
    cancelDelete() {
      this.showConfirmation = false;
    },
    confirmDelete() {
      let vm = this;
      vm.showConfirmation = false;
      axios.delete(`/jobs/${vm.statusData.job.id}/statuses/${vm.statusData.id}`)
          .then(res => {
            vm.$root.$emit('aside', '', {});
            vm.$store.commit('set', ['asideShow', false])
            vm.saving = false;
            vm.$root.$emit('toast', {
              title: 'Status Deleted',
              content: `<b>${vm.statusData.name}</b> has been deleted.`,
            });
          })
          .catch(function (error) {
            vm.$root.$emit('toast', {
              title: 'Unable to delete status',
              content: `There was a problem deleting <b>${vm.statusData.name}</b>, please try again.`,
              color: 'danger'
            });
          });
    }
  },
  mounted() {
    this.setWatchers();
  }
}
</script>