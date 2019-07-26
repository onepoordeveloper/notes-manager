import { Component } from '@angular/core';
import { notStrictEqual } from 'assert';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss', "../../node_modules/bootstrap/dist/css/bootstrap.min.css"]
})
export class AppComponent {
  title = 'Notes Manager';
  editMode = null; // any id within this variable will tell the frontend to put the input field rather than the text
  addNote = function(e){
    if (e.keyCode == 13 && e.target.value.trim()){
      this.notes.push({id:notStrictEqual.length,comment:e.target.value,dateTime:Date.now()});
      e.target.value="";
    }
  }
  updateNote = function(e,n){
    if (e.keyCode == 13){
      n.comment = e.target.value;
      n.dateTime = Date.now();
      this.editMode = null;
    }
  }
  deleteNote = function(i){
    if (confirm(`Are you sure you want to delete this note?`)){
      this.notes.splice(i,1);
      this.editMode = null;
    }
  }

  // initializing notes array - this will be replaced by fetch
  notes = [
    {
      id: "1",
      comment: "description for sub1",
      dateTime: 1564171403492
    },
    {
      id: "2",
      comment: "description for sub2",
      dateTime: 1564171403492
    },
    {
      id: "3",
      comment: "description for sub3",
      dateTime: 1564171403492
    }
  ]
}
