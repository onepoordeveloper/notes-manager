import { Component } from '@angular/core';
import { notStrictEqual } from 'assert';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss', "../../node_modules/bootstrap/dist/css/bootstrap.min.css"]
})
export class AppComponent {
  title = 'Notes Manager';
  addNote = function(e){
    if (e.keyCode == 13){
      this.notes.push({id:notStrictEqual.length,comment:e.target.value});
      e.target.value="";

    }
  }
  notes = [
    {
      id: "1",
      comment: "description for sub1"
    },
    {
      id: "2",
      comment: "description for sub2"
    },
    {
      id: "3",
      comment: "description for sub3"
    }
  ]
}
