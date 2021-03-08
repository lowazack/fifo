const JobStatusModel = {
	id: '',
	name: '',
	color: 'white',
	completed: false,
	job: {},
	job_id: ''
};

const TaskModel = {
	id: '',
	user_id: '',
	job_id: '',
	status_id: '',
	estimate: '',
	quote: '',
	deadline: '',
	priority: 0,
	summary: '',
	description: '',
	complete: false,
	job: {},
	user: {},
	status: {}
}

const TimeEntryModel = {
	id: null,
	user_id: null,
	activity_id: null,
	task_id: null,
	description: null,
	start: null,
	end: null,
	duration: null,
	minutes: '0'
}

module.exports = {
	JobStatusModel,
	TaskModel,
	TimeEntryModel
}