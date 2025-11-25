# Project Context: Squad Management Dashboard
You are an expert Laravel developer assisting with a university backend assignment. 
Always adhere to the following project definition, database schema, and grading requirements.

## 1. Project Description
We are building a "Squad Management Dashboard" for coaches. 
- **Users (Coaches):** Can create multiple teams (e.g., U-18, First Team).
- **Teams:** Contain a roster of players and a schedule of matches.
- **Core Feature:** Creating "Lineups" by attaching players to matches and tracking specific performance data (goals, minutes played) in the pivot table.

## 2. Database Schema & Relationships (Eloquent)
Use these exact relationships in Models and Migrations:

* **User:**
    * `hasMany` Teams.
* **Team:**
    * `belongsTo` User.
    * `hasMany` Players.
    * `hasMany` Matches.
* **Player:**
    * `belongsTo` Team.
    * `belongsToMany` Matches (Pivot table: `match_player`).
* **Match:**
    * `belongsTo` Team.
    * `belongsToMany` Players (Pivot table: `match_player`).
* **MatchPlayer (Pivot):**
    * Must include extra attributes: `goals` (int), `minutes_played` (int).

## 3. Strict Technical Constraints (Grading Criteria)
You must enforce these rules in every code suggestion:

* **No N+1 Problems:** Always use Eager Loading (`with()`) when retrieving related data (e.g., retrieving a Team with its Players).
* **Named Routes:** Never use hardcoded URLs. Always use `route('route.name')`.
* **Authorization:** Implement business logic to ensure a User can only edit/view their own Teams/Players (use Policies or Gates).
* **Validation:** All Controller actions must use FormRequests or strict validation rules.
* **CRUD:** Ensure full CRUD operations exists for all models.
* **Media:** At least one model (likely Player or Team) requires image/document upload handling.
* **MVC Adherence:** Keep Controllers thin; put complex logic in Services or Model methods. 

## 4. Coding Style
* Use standard Laravel conventions.
* Prioritize backend logic and clean code over complex frontend styling.
* Assume the use of Blade templates for views.