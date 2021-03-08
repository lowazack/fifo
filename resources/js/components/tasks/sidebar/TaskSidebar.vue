<template>
	<CTabs tabs class="overflow-y-auto">
		<CTab active>
			<template slot="title">
				<CIcon name="cil-list"/>
			</template>
			<CListGroup class="list-group-accent">
				<CListGroupItem
					class="list-group-item-accent-secondary bg-light
					font-weight-bold text-muted text-uppercase small"
				>
					Task
				</CListGroupItem>
				<CListGroupItem>
					<h5 v-if="taskData.id" class="text-muted"># {{taskData.id}}</h5>
					<InlineEdit editType="textarea" v-model="taskData.summary" size="lg" :required="true"><h2 v-if="taskData.summary">{{ taskData.summary }}</h2><h2 class="text-muted" v-else>Set a task summary</h2></InlineEdit>
				</CListGroupItem>
				<CListGroupItem>
					<InlineEdit editType="vueselect" :options="allJobs" :autoscroll="true" :clearable="false" placeholder="Select a job" v-model="selectedJob" size="lg" :required="true">
						<template v-slot:selected-option="option">
							<CRow class="align-items-center">
								<CCol col="3">
									<span class="color-swatch medium rounded shadow-sm" :class="`fifo-color-purple`"></span>
								</CCol>
								<CCol col="9">
									<h6 class="m-0">{{ option.name }}<br /><small>
										<CButton
										v-for="client in option.clients"
										:key="client.id"
										variant="outline"
										color="secondary"
										class="p-1 m-0 mr-1 mt-1 btn-xs"
										size="sm"
										>{{ client.name }}</CButton>
								    </small></h6>
								</CCol>
							</CRow>
						</template>
						<template v-slot:option="option">
							<CRow class="py-2 align-items-center">
								<CCol col="2">
									<span class="color-swatch medium rounded shadow-sm" :class="`fifo-color-purple`"></span>
								</CCol>
								<CCol col="10">
									<h6 class="m-0">{{ option.name }}<br /><small>
										<CButton
										v-for="client in option.clients"
										:key="client.id"
										variant="outline"
										color="secondary"
										class="p-1 m-0 mr-1 mt-1 btn-xs"
										size="sm"
										>{{ client.name }}</CButton>
      								</small></h6>
								</CCol>
							</CRow>
						</template>
						<div class="d-flex align-items-center" v-if="jobName && allJobs.length > 0">
							<span class="mr-2 color-swatch medium rounded shadow-sm" :class="`fifo-color-purple`"></span>
							<div>
								<h6 class="m-0 text-muted">{{ jobName }}<br /><small>
									<CButton
									v-if="jobClients"
									v-for="client in jobClients"
									:key="client.id"
									variant="outline"
									color="secondary"
									class="p-1 m-0 mr-1 btn-xs"
									size="sm"
									>{{ client.name }}</CButton>
  								</small></h6>
							</div>
						</div>
						<div v-else>
							<h5 class="text-muted"><CIcon name="cil-color-palette"/>&nbsp;&nbsp;Assign to a job</h5>
						</div>
					</InlineEdit>
				</CListGroupItem>
				<CListGroupItem>
					<InlineEdit v-if="taskData.job_id" editType="vueselect" :options="jobStatuses" :autoscroll="true" :clearable="false" placeholder="Select a job" v-model="selectedJobStatus" size="lg" :required="true">
						<template v-slot:selected-option="option">
							<CBadge size="lg" :class="`fifo-color-${option.color}`"><p class="m-0 p-1">{{ option.name | uppercase }}</p></CBadge>
						</template>
						<template v-slot:option="option">
							<CBadge size="lg" :class="`fifo-color-${option.color}`"><p class="m-0 p-1">{{ option.name | uppercase }}</p></CBadge>
						</template>
						<div class="d-flex align-items-center" v-if="taskJobStatus">
							<CBadge size="lg" :class="`fifo-color-${statusColor}`"><p class="m-0 p-1">{{ statusName | uppercase }}</p></CBadge>
						</div>
						<div v-else>
							<h6 class="text-muted"><CIcon name="cil-color-palette"/>&nbsp;&nbsp;Choose a status</h6>
						</div>
					</InlineEdit>
				</CListGroupItem>
				<CListGroupItem>
					<InlineEdit editType="wysiwyg" v-model="taskData.description" :required="true"><span v-if="taskData.description" v-html="taskData.description"></span><span v-else>Add a description to this task</span></InlineEdit>
				</CListGroupItem>
				<CListGroupItem>
					<CRow>
						<CCol class="pb-2">
							<InlineEdit editType="date" v-model="taskData.deadline">
								<small class="text-muted mr-3">
									<CIcon name="cil-calendar"/>&nbsp;&nbsp;{{ taskDate }}
								</small>
							</InlineEdit>
						</CCol>
					</CRow>
					<CRow>
						<CCol class="pb-2">
							<InlineEdit editType="vueselect" :options="allUsers" :autoscroll="true" :clearable="false" placeholder="Select a job" v-model="selectedUser" size="lg" :required="true">
								<template v-slot:selected-option="option">
									<h6 class="py-2 m-0">{{ option.name }}</h6>
								</template>
								<template v-slot:option="option">
									<h6 class="py-2 m-0">{{ option.name }}</h6>
								</template>
								<small v-if="taskData.user_id && allUsers.length > 0" class="text-muted">
									<CIcon name="cil-user"/>&nbsp;&nbsp;{{ assigneeName }}
								</small>
								<small v-else class="text-muted">
									<CIcon name="cil-user"/>&nbsp;&nbsp;Assign to a user
								</small>
							</InlineEdit>
						</CCol>
					</CRow>
					<CRow>
						<CCol class="pb-2">
							<InlineEdit v-model="taskData.estimate" append="hours">
								<small class="text-muted">
									<CIcon name="cil-clock"/>&nbsp;&nbsp;{{ estimate }}
								</small>
							</InlineEdit>
						</CCol>
					</CRow>
					<CRow>
						<CCol>
							<InlineEdit v-model="taskData.quote" append="hours">
								<small class="text-muted">
									<CIcon name="cil-british-pound"/>&nbsp;&nbsp;{{ quote }}
								</small>
							</InlineEdit>
						</CCol>
					</CRow>
				</CListGroupItem>
				<CListGroupItem>
					<CRow>
						<CCol class="d-flex align-items-center">
							<star-rating v-model="taskData.priority" :showRating="false" :starSize="20" :animate="true" :roundedCorners="true" :borderWidth="3" activeColor="#768192" activeBorderColor="#768192" inactiveColor="#d8dbe0" borderColor="#d8dbe0" @current-rating="setTempPriority" @mouseleave.native="unsetTempPriority" />
							<p class="px-2 m-0 text-muted">{{ priorityText }}</p>
						</CCol>
					</CRow>
				</CListGroupItem>
				<CListGroupItem v-if="!taskData.id" tag="div">
					<CLoadingButton @click.native="saveTask()" color="primary" class="mb-2" :loading="saving" :disabled="saving">Save Status</CLoadingButton>
				</CListGroupItem>
			</CListGroup>
		</CTab>
		<CTab v-if="1==2">
			<template slot="title">
				<CIcon name="cil-settings"/>
			</template>
			<CListGroup class="list-group-accent">
				<CListGroupItem
					href="#"
					class="list-group-item-accent-warning list-group-item-divider"
				>
					<div class="c-avatar float-right">
						<img
							class="c-avatar-img"
							src="img/avatars/7.jpg"
							alt="admin@bootstrapmaster.com"
						>
					</div>
					<div>Meeting with
						<strong>Lucas</strong>
					</div>
					<small class="text-muted mr-3">
						<CIcon name="cil-calendar"/>&nbsp;&nbsp;1 - 3pm
					</small>
					<small class="text-muted">
						<CIcon name="cil-location-pin"/>&nbsp;&nbsp;Palo Alto, CA
					</small>
				</CListGroupItem>
				<CListGroupItem href="#" class="list-group-item-accent-info">
					<div class="c-avatar float-right">
						<img
							class="c-avatar-img"
							src="img/avatars/4.jpg"
							alt="admin@bootstrapmaster.com"
						>
					</div>
					<div>Skype with <strong>Megan</strong>
					</div>
					<small class="text-muted mr-3">
						<CIcon name="cil-calendar"/>&nbsp;&nbsp;4 - 5pm
					</small>
					<small class="text-muted">
						<CIcon name="cib-skype"/>&nbsp;&nbsp;On-line
					</small>
				</CListGroupItem>
				<hr class="transparent mx-3 my-0">
				<CListGroupItem
					class="list-group-item-accent-secondary bg-light text-center
					font-weight-bold text-muted text-uppercase small"
				>
					Tomorrow
				</CListGroupItem>
				<CListGroupItem
					href="#"
					class="list-group-item-accent-danger list-group-item-divider"
				>
					<div>New UI Project - <strong>deadline</strong>
					</div>
					<small class="text-muted mr-3">
						<CIcon name="cil-calendar"/>&nbsp;&nbsp;10 - 11pm
					</small>
					<small class="text-muted">
						<CIcon name="cil-home"/>&nbsp;&nbsp;creativeLabs HQ
					</small>
					<div class="c-avatars-stack mt-2">
						<div class="c-avatar c-avatar-xs">
							<img
								class="c-avatar-img"
								src="img/avatars/2.jpg"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								class="c-avatar-img"
								src="img/avatars/3.jpg"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								class="c-avatar-img"
								src="img/avatars/4.jpg"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								class="c-avatar-img"
								src="img/avatars/5.jpg"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								class="c-avatar-img"
								src="img/avatars/6.jpg"
								alt="admin@bootstrapmaster.com"
							>
						</div>
					</div>
				</CListGroupItem>
				<CListGroupItem
					href="#"
					class="list-group-item-accent-success list-group-item-divider"
				>
					<div><strong>#10 Startups.Garden</strong> Meetup</div>
					<small class="text-muted mr-3">
						<CIcon name="cil-calendar"/>&nbsp; 1 - 3pm
					</small>
					<small class="text-muted">
						<CIcon name="cil-location-pin"/>&nbsp; Palo Alto, CA
					</small>
				</CListGroupItem>
				<CListGroupItem
					href="#"
					class="list-group-item-accent-primary list-group-item-divider"
				>
					<div><strong>Team meeting</strong></div>
					<small class="text-muted mr-3">
						<CIcon name="cil-calendar"/>&nbsp; 4 - 6pm
					</small>
					<small class="text-muted">
						<CIcon name="cil-home"/>&nbsp; creativeLabs HQ
					</small>
					<div class="c-avatars-stack mt-2">
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/2.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/3.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/4.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/5.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/6.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
						<div class="c-avatar c-avatar-xs">
							<img
								src="img/avatars/8.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
						</div>
					</div>
				</CListGroupItem>
			</CListGroup>
		</CTab>
		<CTab v-if="1==2">
			<template slot="title">
				<CIcon name="cil-pencil"/>
			</template>
			<div class="p-3">
				<CInput
					label="Company"
					placeholder="Enter your company name"
					name="summary"
					v-model="task.summary"
				/>
				<CInput
					label="VAT"
					placeholder="PL1234567890"
				/>
				<CInput
					label="Street"
					placeholder="Enter street name"
				/>
				<CRow>
					<CCol sm="8">
					<CInput
						label="City"
						placeholder="Enter your city"
					/>
					</CCol>
					<CCol sm="4">
					<CInput
						label="Postal code"
						placeholder="Postal code"
					/>
					</CCol>
				</CRow>
				<CInput
					label="Country"
					placeholder="Country name"
				/>
			</div>
		</CTab>
		<CTab v-if="1==2">
			<template slot="title">
				<CIcon name="cil-pencil"/>
			</template>
			<div class="p-3">
				<div class="message">
					<div class="py-3 pb-5 mr-3 float-left">
						<div class="c-avatar">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
							<span class="bg-success c-avatar-status"></span>
						</div>
					</div>
					<div>
						<small class="text-muted">Lukasz Holeczek</small>
						<small class="text-muted float-right mt-1">1:52 PM</small>
					</div>
					<div class="text-truncate font-weight-bold">
						Lorem ipsum dolor sit amet
					</div>
					<small class="text-muted">{{lorem}}</small>
				</div>
				<hr>
				<div class="message">
					<div class="py-3 pb-5 mr-3 float-left">
						<div class="c-avatar">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
							<span class="bg-success c-avatar-status"></span>
						</div>
					</div>
					<div>
						<small class="text-muted">Lukasz Holeczek</small>
						<small class="text-muted float-right mt-1">1:52 PM</small>
					</div>
					<div class="text-truncate font-weight-bold">
						Lorem ipsum dolor sit amet
					</div>
					<small class="text-muted">{{lorem}}</small>
				</div>
				<hr>
				<div class="message">
					<div class="py-3 pb-5 mr-3 float-left">
						<div class="c-avatar">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
							<span class="bg-success c-avatar-status"></span>
						</div>
					</div>
					<div>
						<small class="text-muted">Lukasz Holeczek</small>
						<small class="text-muted float-right mt-1">1:52 PM</small>
					</div>
					<div class="text-truncate font-weight-bold">
						Lorem ipsum dolor sit amet
					</div>
					<small class="text-muted">{{lorem}}</small>
				</div>
				<hr>
				<div class="message">
					<div class="py-3 pb-5 mr-3 float-left">
						<div class="c-avatar">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
							<span class="bg-success c-avatar-status"></span>
						</div>
					</div>
					<div>
						<small class="text-muted">Lukasz Holeczek</small>
						<small class="text-muted float-right mt-1">1:52 PM</small>
					</div>
					<div class="text-truncate font-weight-bold">
						Lorem ipsum dolor sit amet
					</div>
					<small class="text-muted">{{lorem}}</small>
				</div>
				<hr>
				<div class="message">
					<div class="py-3 pb-5 mr-3 float-left">
						<div class="c-avatar">
							<img
								src="img/avatars/7.jpg"
								class="c-avatar-img"
								alt="admin@bootstrapmaster.com"
							>
							<span class="bg-success c-avatar-status"></span>
						</div>
					</div>
					<div>
						<small class="text-muted">Lukasz Holeczek</small>
						<small class="text-muted float-right mt-1">1:52 PM</small>
					</div>
					<div class="text-truncate font-weight-bold">
						Lorem ipsum dolor sit amet
					</div>
					<small class="text-muted">{{lorem}}</small>
				</div>
			</div>
		</CTab>
		<CTab v-if="1==2">
			<template slot="title">
				<CIcon name="cil-settings"/>
			</template>
			<div class="p-3">
				<h6>Settings</h6>
				<div>
					<div class="clearfix mt-4">
						<small><b>Option 1</b></small>
						<CSwitch
							color="success"
							labelOn="On"
							labelOff="Off"
							shape="pill"
							size="sm"
							class="float-right"
							checked
						/>
					</div>
					<div>
						<small class="text-muted">

						</small>
					</div>
				</div>
				<div>
					<div class="clearfix mt-3">
						<small><b>Option 2</b></small>
						<CSwitch
							color="success"
							labelOn="On"
							labelOff="Off"
							shape="pill"
							size="sm"
							class="float-right"
						/>
					</div>
					<div>
						<small class="text-muted">{{lorem}}</small>
					</div>
				</div>
				<div>
					<div class="clearfix mt-3">
						<small><b>Option 3</b></small>
						<CSwitch
							color="success"
							labelOn="On"
							labelOff="Off"
							shape="pill"
							size="sm"
							class="float-right"
							disabled
							checked
						/>
					</div>
					<div>
						<small class="text-muted">Disabled option.</small>
					</div>
				</div>
				<div>
					<div class="clearfix mt-3">
						<small><b>Option 4</b></small>
						<CSwitch
							color="success"
							labelOn="On"
							labelOff="Off"
							shape="pill"
							size="sm"
							class="float-right"
							checked
						/>
					</div>
				</div>
				<hr>
				<h6>System Utilization</h6>
				<div class="text-uppercase mb-1 mt-4">
					<small><b>CPU Usage</b></small>
				</div>
				<CProgress class="progress-xs" color="info" :value="25"/>
				<small class="text-muted">348 Processes. 1/4 Cores.</small>
				<div class="text-uppercase mb-1 mt-2">
					<small><b>Memory Usage</b></small>
				</div>
				<CProgress class="progress-xs" color="warning" :value="70"/>
				<small class="text-muted">11444MB/16384MB</small>
				<div class="text-uppercase mb-1 mt-2">
					<small><b>SSD 1 Usage</b></small>
				</div>
				<CProgress class="progress-xs" color="danger" :value="95"/>
				<small class="text-muted">243GB/256GB</small>
				<div class="text-uppercase mb-1 mt-2">
					<small><b>SSD 2 Usage</b></small>
				</div>
				<CProgress class="progress-xs" color="success" :value="10"/>
				<small class="text-muted">25GB/256GB</small>
			</div>
		</CTab>
	</CTabs>
</template>
<script>

import { TaskModel } from '@/models';

export default {
	name: 'TaskSidebar',
	data () {
		return {
			taskData: {},
			taskDataOriginal: {},
			unwatch: null,
			unwatchJobs: null,
			unwatchJobsStatuses: null,
			unwatchUsers: null,
			priorityHover: false,
			allJobs: [],
			allUsers: [],
			jobStatuses: [],
			selectedJob: '',
			selectedJobStatus: '',
			selectedUser: '',
			saving: false
		}
	},
	props: {
		task: {
			type: Object
		}
	},
	watch: {
		task: function() {
			if('id' in this.task && 'id' in this.taskData && this.task.id !== this.taskData.id){
				this.unwatch();
				this.unwatchJobs();
				this.unwatchJobsStatuses();
				this.unwatchUsers();
				this.setWatchers();
			}
		}
	},
	computed: {
		taskDate(){
			return (this.taskData.deadline) ? this.$helpers.moment(this.taskData.deadline).format("dddd, MMM Do YYYY") : 'Set a deadline';
		},
		userName(){
			return (this.taskData.user) ? this.taskData.user.first_name+' '+this.taskData.user.last_name : 'Assign to a user'
		},
		estimate(){
			return (this.taskData.estimate) ? 'Estimated: '+this.taskData.estimate+'h' : 'Set an estimate'
		},
		quote(){
			return (this.taskData.quote) ? 'Quoted: '+this.taskData.quote+'h' : 'Set quoted time'
		},
		taskJob(){
			let vm = this;
			return this.allJobs.find(j => j.id === vm.taskData.job_id);
		},
		taskJobStatus(){
			let vm = this;
			return this.jobStatuses.find(js => js.id === vm.taskData.status_id);
		},
		taskAssignee(){
			let vm = this;
			return this.allUsers.find(u => u.id === vm.taskData.user_id);
		},
		jobName(){
			return (this.taskJob && 'name' in this.taskJob) ? this.taskJob.name : false;
		},
		jobClients(){
			return (this.taskJob && 'clients' in this.taskJob) ? this.taskJob.clients : false;
		},
		statusName(){
			return (this.taskJobStatus && 'name' in this.taskJobStatus) ? this.taskJobStatus.name : false;
		},
		statusColor(){
			return (this.taskJobStatus && 'color' in this.taskJobStatus) ? this.taskJobStatus.color : false;
		},
		assigneeName(){
			return this.taskAssignee.name;
		},
		priorityText(){
			var priorities = {
				'1': 'No priority',
				'2': 'Low priority',
				'3': 'Medium priority',
				'4': 'High priority',
				'5': 'Urgent'
			}
			return (this.priorityHover) ? priorities[this.priorityHover] : priorities[this.taskData.priority] ;
		}
	},
	methods: {
		setTempPriority(priority){
			this.priorityHover = priority;
		},
		unsetTempPriority(){
			this.priorityHover = false;
		},
		setWatchers(){
			let vm = this;
			if(!vm.task){
				vm.taskData = vm.$helpers.clone(TaskModel)
				axios.get(`/jobs`).then(res => {
					vm.allJobs = res.data
				})
				axios.get(`/users`).then(res => {
					vm.allUsers = res.data
				})
				vm.selectedJob = false;
				vm.selectedJobStatus = false;
				vm.selectedUser = false;
				vm.unwatchJobs = vm.$watch('selectedJob', function() {
					if(vm.taskData.job_id != vm.selectedJob.id){
						vm.taskData.job_id = vm.selectedJob.id
						vm.taskData.status_id = null
						vm.selectedJobStatus = null
						axios.get(`/jobs/${vm.selectedJob.id}/statuses`).then(res => {
							vm.jobStatuses = res.data
						})
					}
				}, { deep: true });
				vm.unwatchJobsStatuses = vm.$watch('selectedJobStatus', function() {
					vm.taskData.status_id = (vm.selectedJobStatus && 'id' in vm.selectedJobStatus) ? vm.selectedJobStatus.id : null;
				}, { deep: true });
				vm.unwatchUsers = vm.$watch('selectedUser', function() {
					vm.taskData.user_id = vm.selectedUser.id
				}, { deep: true });
			}else{
				vm.taskData = vm.task
				vm.taskDataOriginal = vm.$helpers.clone(vm.task)
				vm.selectedJob = vm.task.job;
				vm.selectedJobStatus = vm.task.status;
				vm.selectedUser = vm.task.user;
				axios.get(`/tasks/${vm.task.id}?filter[withcompleted]=1`).then(res => {
					vm.taskData = res.data
					vm.taskDataOriginal = vm.$helpers.clone(res.data);
					axios.get(`/jobs`).then(res => {
						vm.allJobs = res.data
					})
					axios.get(`/users`).then(res => {
						vm.allUsers = res.data
					})
					axios.get(`/jobs/${vm.taskData.job_id}/statuses`).then(res => {
						vm.jobStatuses = res.data
					})
					vm.unwatchJobs = vm.$watch('selectedJob', function() {
						if(vm.taskData.job_id != vm.selectedJob.id){
							vm.taskData.job_id = vm.selectedJob.id
							vm.taskData.status_id = null
							vm.selectedJobStatus = null
							axios.get(`/jobs/${vm.selectedJob.id}/statuses`).then(res => {
								vm.jobStatuses = res.data
							})
						}
					}, { deep: true });
					vm.unwatchJobsStatuses = vm.$watch('selectedJobStatus', function() {
						vm.taskData.status_id = (vm.selectedJobStatus && 'id' in vm.selectedJobStatus) ? vm.selectedJobStatus.id : null;
					}, { deep: true });
					vm.unwatchUsers = vm.$watch('selectedUser', function() {
						vm.taskData.user_id = vm.selectedUser.id
					}, { deep: true });
					vm.unwatch = vm.$watch('taskData', function() {
						if(vm.$helpers.hashObject(vm.taskData) !== vm.$helpers.hashObject(vm.taskDataOriginal)){
							axios.put(`/tasks/${vm.taskData.id}?filter[withcompleted]=1`, vm.taskData).then(res => {
								vm.taskData = res.data
								vm.taskDataOriginal = vm.$helpers.clone(res.data);
							});
							vm.$root.$emit('toast', {
								title: 'Task Updated',
								content: `Changes to <b>${vm.taskData.summary}</b> have been saved.`,
							});
						}
					}, { deep: true });
				});
			}
		},
		saveTask(){
			let vm = this;
			vm.saving = true;
			axios.post(`/tasks`, vm.taskData)
			.then(res => {
				vm.$root.$emit('aside', '', {});
      			vm.$store.commit('set', ['asideShow', false])
      			vm.saving = false;
      			vm.$root.$emit('toast', {
					title: 'Task Added',
					content: `The task <b>${vm.taskData.name}</b> has been created.`,
				});
			})
			.catch(function (error) {
			    vm.$root.$emit('toast', {
					title: 'Unable to save task',
					content: `There was a problem saving <b>${vm.taskData.name}</b>, please try again.`,
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
<style lang="scss" scoped>
 .vue-star-rating-star-rotate:hover {
    transition: transform .25s ease-in-out !important;
    transform: scale(1.3) !important;
}
</style>
