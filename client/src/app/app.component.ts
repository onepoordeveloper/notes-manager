import { Component, OnInit } from "@angular/core";
import { notStrictEqual } from "assert";
import { DataService } from "./data.service";
import { Note } from "./note.model";

@Component({
  selector: "app-root",
  templateUrl: "./app.component.html",
  styleUrls: [
    "./app.component.scss",
    "../../node_modules/bootstrap/dist/css/bootstrap.min.css"
  ]
})
export class AppComponent {
  notes: Note[];
  constructor(private _dataService: DataService) {}
  title = "Notes Manager";
  editMode = null; // any id within this variable will tell the frontend to put the input field rather than the text
  addNote = function(e) {
    if (e.keyCode == 13 && e.target.value.trim()) {
      this._dataService
        .addNote({ id: null, comment: e.target.value, dateTime: Date.now() })
        .subscribe(data => {
          this.fetchNotes();
        });
      e.target.value = "";
    }
  };
  updateNote = function(e, n) {
    if (e.keyCode == 13) {
      n.comment = e.target.value;
      n.dateTime = Date.now();
      this._dataService.updateNote(n).subscribe(data => {
        this.fetchNotes();
      });
      this.editMode = null;
    }
  };
  deleteNote = function(id) {
    this.editMode = null;
    if (confirm(`Are you sure you want to delete this note?`)) {
      this._dataService.deleteNote(id).subscribe(data => {
        this.fetchNotes();
      });
    }
  };

  fetchNotes = function() {
    return this._dataService.getNotes().subscribe(response => {
      this.notes = response.data;
    });
  };

  // initializing notes array - this will be replaced by fetch

  ngOnInit() {
    this.fetchNotes();
  }
}
