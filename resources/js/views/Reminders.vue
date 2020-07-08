<template>
  <div>
    <div v-if="loading">
      <div class="spinner" v-if="loading">
        <i class="fas fa-sync-alt fa-spin"></i>
      </div>
    </div>
    <div class="reminders" v-else>
      <table>
        <tr>
          <th>Due date</th>
          <th>Message</th>
          <th>Cancel</th>
        </tr>
        <tr v-for="reminder in reminders" :key="reminder.id">
          <td>{{reminder.due}}</td>
          <td>{{reminder.message}}</td>
          <td>
            <button @click="cancel(reminder.id)" class="cancel">Cancel</button>
          </td>
        </tr>
      </table>
      <form @submit="submit">
        <input type="datetime-local" v-model="newReminder.date" />
        <textarea v-model="newReminder.message" placeholder="Reminder message"></textarea>
        <button class="btn btn-primary">Set reminder</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      reminders: [],
      newReminder: {
        date: this.getTime(),
        message: ""
      }
    };
  },
  mounted() {
    this.load();

    Echo.private(`reminder.sent.${this.User.id}`).listen("ReminderSent", e => {
      this.load();
    });
  },
  methods: {
    load() {
      axios
        .get("/api/reminders")
        .then(res => {
          this.reminders = res.data;
          this.loading = false;
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Error loading reminders",
            message:
              "Something went wrong loading your reminders, please try again later."
          });
        });
    },

    cancel(id) {
      axios
        .post("/api/reminders/cancel", { id })
        .then(res => {
          this.$notify({
            type: "success",
            title: "Reminder cancelled",
            message: "This reminder has been cancelled."
          });
          this.load();
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Error Cancelling reminder",
            message:
              "Something went wrong cancelling this reminder, please try again later."
          });
        });
    },

    submit(e) {
      e.preventDefault();

      let setDate = new Date(this.newReminder.date);
      let now = new Date();
      let diff = setDate - now;
      let message = this.newReminder.message;

      if (diff < 0) {
        this.$notify({
          type: "warn",
          title: "Must be a future time!",
          message: "You can not set reminders for a past date"
        });
        return;
      }

      axios
        .post("/api/reminders/set", {
          diff,
          message,
          due: this.convertTime(setDate, true)
        })
        .then(res => {
          this.$notify({
            type: "success",
            title: "Reminder set!",
            message: "Your reminder has been set!"
          });
          this.load();
          this.newReminder = {
            date: this.getTime(),
            message: ""
          };
        });
    },

    getTime() {
      let time = this.convertTime(new Date());
      return time.split(":")[0] + ":" + time.split(":")[1];
    },

    convertTime(date, humanReadable = false) {
      let dt = new Date(date.toLocaleString("en-US")),
        current_date = dt.getDate(),
        current_month = dt.getMonth() + 1,
        current_year = dt.getFullYear(),
        current_hrs = dt.getHours(),
        current_mins = dt.getMinutes();

      current_date = current_date < 10 ? "0" + current_date : current_date;
      current_month = current_month < 10 ? "0" + current_month : current_month;
      current_hrs = current_hrs < 10 ? "0" + current_hrs : current_hrs;
      current_mins = current_mins < 10 ? "0" + current_mins : current_mins;

      if (humanReadable) {
        return (
          current_date +
          "/" +
          current_month +
          "/" +
          current_year +
          " " +
          current_hrs +
          ":" +
          current_mins
        );
      } else {
        return (
          current_year +
          "-" +
          current_month +
          "-" +
          current_date +
          "T" +
          current_hrs +
          ":" +
          current_mins
        );
      }
    }
  },
  computed: {
    User() {
      return this.$store.state.User.User;
    }
  }
};
</script>

<style lang="scss" scoped>
.reminders {
  position: absolute;
  top: 10%;
  left: 50%;
  transform: translate(-50%, 0%);
  color: white;
}

.reminders > table {
  overflow-y: scroll;

  tr {
    border-bottom: 1px solid white;
  }

  td,
  th {
    padding: 15px;
    text-align: center;
    vertical-align: middle;
  }
  button.cancel {
    background-color: red;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 5px;
  }
}

.reminders > form {
  margin-top: 50px;
  input,
  textarea {
    width: 100%;
    margin-bottom: 5px;
    border: 1px solid black;
    border-radius: 5px;
    padding: 5px;
  }
  textarea:focus,
  input:focus {
    outline: none;
  }
  textarea {
    resize: none;
    height: 200px;
  }
  button {
    border: none;
    border-radius: 5px;
    padding: 5px;
    margin-bottom: 20px;
  }
}
</style>