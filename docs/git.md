# Git

## Quick Reference

WIP

## Issues

Issues are used to keep track of what needs to be changed for this project. When logging an issue, be sure to assign the following, if possible:

- Assignee
- Time tracking estimate
- Due Date
- Labels
  - Priority
  - Status
  - Type

### Assignee

If a specific developer would be best suited to address an issue, assign them to it so that they know they need to look at it.

### Time Tracking Estimate

If using GitLab, time tracking estimates can be set by adding `/estimate 1mo 2w 3d 10h 30m 15s` to the issue body or a comment. For example, if a change was quoted at 3 hours, add `/estimate 3h`.

### Due Date

If using GitLab, due dates can be set by selecting a date in the "Due Date" field when creating an issue, or clicking "Edit" under "Due date" in an issues sidebar. If a change needs to be completed by a specific date, a due date should be set.

### Labels

This project uses a variation of ["Sane" labels](https://medium.com/@dave_lunny/sane-github-labels-c5d2e6004b63). In essence, each issue should be labeled with a Priority, a Status, and a Type. This helps keep track of what needs done first, how to approach it, and what phase of development it's in.

## Commit Formatting

Commit messages should be descriptive, but concise. They should describe what the commit does in an easily understandable manor, so as not to confuse any future developers reading through past commits.

Commits should be formatted in the present imperative tense. This is because when you commit something, you are describing the action of the commit, not you did before the commit. Git itself recommends and uses present imperative tense, so matching that style is ideal.

When a commit is in relation to a specific issue, that issue should be mentioned after a semicolon at the end of the message, with either "Address" or "Close" prefixing it. "Address" should be used if the commit partially addresses an issue, but doesn't completely solve it. "Close" should be used if the commit completely addresses all aspects of the issue.

If a commit requires more details than a single line, do so by adding two line breaks after the message, then entering any additional required information. This can be useful if the way something was implemented was less than ideal, confusing, or unclear, as additional the additional information can allow you to explain your rationnel.

Some examples appear below.

**Good commit messages:**

- `Adjust line-height for navigation links; Close #72`
- ```
  Prevent infinite loop when no match found in menu list while loop

  This prevents a memory overflow from occuring when viewing a page which doesn't appear in the menu.
  ```
- `Ensure that users cannot submit a blank email address; Address #43`

**Bad commit messages:**

- `Changed color of links from red to green`
- `Fixed a thing`
- `Allow user to set link color via a setting; Fixed #17`

## Branching

This project has two persistent branches: `master` and `release`.

`master` is the most current, stable version of the project, which is typically what is put live on a website. No one should ever commit directly to master.

`release` contains any changes that are still in flux, or undergoing active development. Once a change, or set of changes, is complete, and a build package is created (see [Deployment](deployment.md#distribution-releases)), a merge/pull request is used to merge `release` in to `master`. Typically, only the project lead should develop out of `release`, and any additional developers should work out of their own branches.

Unless you're the project lead for this repository, when you make a change, you should create a new branch off of `release`. The branch should be named as `your_username-2019-05-21`, `specific-feature-description`, or similar. Once your changes are complete, a Merge/Pull request should be made from your branch in to `release`.

## Merge/Pull Requests

When a change is complete, a merge/pull request should be made to pull the updated code in to `release`.

**Note:** These instructions do not cover merge in to `master`. For more information on that process, see [Deployment](deployment.md#distribution-releases).

Briefly describe the change in the "Title" field, and provide any relevant details in the "Description" field. Additionally, be sure to set the following settings as appropriate.

- Assignee (see [Issues &rarr; Assignee](#assignee))
- Labels (see [Issues &rarr; Labels](#labels))
  - Priority
  - Status
  - Type
- Source branch (should always be your new branch)
- Tarbet branch (should always be `release`)
- [x] Delete Delete source branch when merge request is accepted.
- [ ] Squash commits when merge request is accepted.

Once a merge request is submitted, a CI pipeline will trigger. If this passes, the merge request may be accepted, or the project lead (the assignee) may request that further changes be made before merging.
