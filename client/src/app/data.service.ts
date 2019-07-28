import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Note } from "./note.model";

@Injectable({
  providedIn: "root"
})
export class DataService {
  URLbase = "http://localhost/notes-manager/server/api.php?";
  getNotesURL = this.URLbase + "operation=getNotes";
  private addNoteURL = this.URLbase + "operation=addNote";
  private updateNoteURL =
    "http://localhost/notes-manager/server/api.php?operation=updateNote";
  private deleteNoteURL = this.URLbase + "operation=deleteNote";
  constructor(private http: HttpClient) {}
  public getNotes = () => {
    return this.http.get<any>(this.getNotesURL);
  };
  public addNote = note => {
    return this.http.post<Note>(this.addNoteURL, JSON.stringify(note));
  };
  public updateNote = note => {
    return this.http.post<Note>(this.updateNoteURL, JSON.stringify(note));
  };
  public deleteNote = id => {
    return this.http.post(this.deleteNoteURL, { id });
  };
}
