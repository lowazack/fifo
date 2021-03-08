<template>
    <CCard class="m-0">
        <CCardBody>
            <CRow>
                <CCol col="12" class="mb-2">
                    <label for="projecttask">Project / task</label>
                    <Multiselect
                        id="projecttask"
                        class="multiselect-sm"
                        v-model="timeEntry.task_id"
                        :options="projectsTasks"
                        deselect-label="Cannot unselect"
                        track-by="id"
                        label="summary"
                        placeholder="Select one"
                        :searchable="true"
                        :allow-empty="false"
                        group-values="tasks"
                        group-label="job"
                    >
                        <template slot="option" slot-scope="{ option }">
              <span v-if="option.task_number"><span class="text-muted">[{{
                      option.task_number
                  }}]</span> {{ option.summary }}</span>
                            <span v-else>{{ option.$groupLabel }}</span>
                        </template>
                    </Multiselect>
                </CCol>
            </CRow>
            <CRow>
                <CCol col="12" class="mb-2">
                    <label for="activity">Activity</label>
                    <Multiselect
                        id="activity"
                        v-model="timeEntry.activity_id"
                        :options="activities"
                        deselect-label="Cannot unselect"
                        track-by="id"
                        label="name"
                        placeholder="Select one"
                        :searchable="true"
                        :allow-empty="false"
                    />
                </CCol>
            </CRow>
            <CRow>
                <CCol col="12" class="mb-2">
                    <CTextarea
                        id="description"
                        label="Description"
                        v-model="timeEntry.description"
                    />
                </CCol>
            </CRow>
            <CRow>
                <CCol col="6">
                    <CLoadingButton
                        @click.native="saveTimeEntry()"
                        color="primary col-12 btn-xl"
                        class="mb-2"
                        :loading="saving"
                        :disabled="saving"
                        v-if="!timeEntry.duration"
                    >
                        Start timer
                    </CLoadingButton>
                    <CLoadingButton
                        @click.native="saveTimeEntry()"
                        color="primary col-12 btn-xl"
                        class="mb-2"
                        :loading="saving"
                        :disabled="saving"
                        v-if="timeEntry.duration"
                    >Save
                    </CLoadingButton>
                </CCol>
                <CCol col="3" class="offset-3">
                    <CRow>
                        <CInput
                            class="col-12"
                            addInputClasses="form-control-xl"
                            placeholder="0:00"
                            v-model="timeEntry.duration"
                        />
                    </CRow>
                </CCol>
            </CRow>
        </CCardBody>
    </CCard>
</template>

<script>

import {TimeEntryModel} from '@/models';
import moment from "moment";

export default {
    name: 'Stopwatch',
    data() {
        return {
            projectsTasks: [],
            activities: [],
            activity: '',
            timeEntry: TimeEntryModel,
            saving: false,
        }
    },
    computed: {
        entryFormObject: function () {
            let formObject = {};

            Object.assign(formObject, this.timeEntry)

            formObject.start = moment().format('YYYY-MM-DD HH:mm:ss')

            if (this.timeEntry.task_id) {
                formObject.task_id = this.timeEntry.task_id.id;
            }
            if (this.timeEntry.activity_id) {
                formObject.activity_id = this.timeEntry.activity_id.id;
            }
            return formObject;
        }
    },
    methods: {
        selectOptionTask({task_number, summary}) {
            return `[${task_number}] ${summary}`
        },
        saveTimeEntry() {
            this.saving = true;
            axios.post(`/time-entries`, this.entryFormObject)
                .then(() => {
                    this.saving = false;
                    this.timeEntry = TimeEntryModel
                })
                .catch(() => {
                    this.$root.$emit('toast', {
                        title: 'Unable to create time entry',
                        content: `There was a problem saving, please try again.`,
                        color: 'danger'
                    });
                });
        },
    },
    mounted() {
        axios.getAll(`/activities`).then(res => {
            this.activities = res.data;
        })

        axios.getAll(`/tasks`).then(res => {
            let distinctJobs = []
            let result = [];

            res.data.forEach((task) => {
                if (!distinctJobs.includes(task.job_id)){
                    distinctJobs.push(task.job_id)
                }
            })

            distinctJobs.forEach(jobId => {
                let tasks = res.data.filter(task => {
                    return task.job_id === jobId
                })

                result.push({
                    job: tasks[0].job.name,
                    tasks: tasks
                })
            })

            this.projectsTasks = result
        })
    }
}
</script>

<style lang="scss"></style>
