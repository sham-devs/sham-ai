# Durable References Rules (The Sole Governing Reference)

> **Status:** This file is **the single source of truth** for everything related to the `Documentation/References/` folder — what belongs in it, what does not, naming and content rules, folder classification, and the gateway for adding a new reference. Any prior reference document §24 redirects here; this document is the direct replacement for `supervisor-rules.md` §24. Any conflict between this file and any other reference in `.llmrules/` files or elsewhere — **this file prevails.**
>
> `Documentation/References/README.md` is the display map for the existing files only (it does not repeat the rules).

---

## The Golden Rule

If you are asked "does this file belong to References?" --- the answer is determined based on: will a future developer or agent need to return to this content to understand **a decision or mechanism**? If the answer is yes → yes. If the answer is no (the content describes only what was done) → no.

---

## Folder Classification (Durable Structure)

The `Documentation/References/` folder is organized into five semantic subfolders. Naming is by concept, not by numbering (numeric prefixes were permanently dropped in favor of clear names):

| Folder | Category | Examples |
|--------|----------|----------|
| `Architecture/` | The comprehensive architecture document + decisions + adopted mechanisms | `System_Architecture_Document.md` (SAD — the single entry point), `Decisions/Architecture_Decisions.md`, `Auth_Authorization_Architecture.md`, `Facade_Architecture.md` |
| `Architecture/Decisions/` | Records of architectural decisions and Won't-Do decisions | `Architecture_Decisions.md`, `Wont_Do_Decisions.md` |
| `Infrastructure/` | Cross-platform, deployment, and environment decisions | `Deployment_Model_Framework_Dependent.md`, `CI_CD_Governing_System.md`, `Linux_Dev_Environment_Architecture.md` |
| `Integrations/` | External API contracts and integration mechanisms | `License_Server_API.md`, `License_Polling_Architecture.md` |
| `Assets/` | Asset strategies and conventions | `Documentation_Assets_Strategy.md`, `Download_File_Naming_Convention.md` |
| `Operations/` | Operational and procedural references (build, deploy, environment, release) | `Build_Guide.md`, `DEPLOYMENT_ENVIRONMENT.md`, `DEV_ENVIRONMENT.md`, `RELEASE_DOCUMENTATION_CHECKLIST.md` |

**Rules attached to the structure:**
- Naming is semantic not numeric; there are no numeric prefixes, and no mandatory reading order — the reading order is guided by the SAD.
- `System_Architecture_Document.md` resides in `Architecture/` (not the References root) — the single architectural entry point.
- The operations folder `Operations/` accommodates operational/procedural references (build/deploy/environment/release guides). These references **differ in nature** from decisions and mechanisms: they describe "how a procedure is performed," not "what is the decision and why," but they are durable references that a future developer/release engineer turns to, so they deserve a place in References under their dedicated category.
- The cross-folder referencing pattern: **filename only** (e.g., `↳ Deployment_Model_Framework_Dependent.md §6`) — no relative path between folders, because it is simpler to maintain and is unaffected by reorganization.
- **Classificatory alert:** If an operational file exists that inherently contains an "adopted architectural mechanism" (such as `Microbiology_Hierarchical_Results.md`), it is more correct to move it to `Architecture/`, not `Operations/`. Review the files moved from `Operations/` during review to ensure their correct classification.

---

## What Belongs Here Exclusively

1. **Architectural decisions:** why we chose X instead of Y (with context, justifications, and rejected alternatives)
2. **Adopted mechanisms:** how a particular system works and why it was designed this way (a reference for the future)
3. **Complex architectural analyses:** explaining the reason for a particular system or data flow
4. **External API contracts:** external services we deal with, detailing requests and responses
5. **Won't-Do decisions:** justifications for rejecting certain features and the conditions for reopening them
6. **Adopted operational references:** guides for established procedures (build, deploy, environment setup, release) that a future developer/release engineer turns to for repeated execution — placed in `Operations/`

---

## What Does NOT Belong Here Ever (Prohibitions)

1. **Phase closeout minutes:** these contain "what was done," not "what is the decision" → extract only the decision and place it in a separate reference
2. **Legacy code archives:** do not belong in durable references
3. **Phased tracking registers:** temporary operational tools
4. **Operational testing tools:** temporary tools, not decisions
5. **Deferred feature designs:** go to `Planning/Backlog/`
6. **Execution logs:** including phase tables, lists of modified files, and commit hashes
7. **Finished plans:** do not transfer them in full to References --- extract only the decision from them

---

## Naming and Content Rules

> `.llmrules/` files use kebab-case (see `code-gear1.md §3.5`). `Documentation/References/` uses `Pascal_Snake_Case`. This separation is intentional.

* **Naming by concept or mechanism:** not by the name of the task, plan, or phase
  * Correct: `Auth_Authorization_Architecture.md`, `License_Polling_Architecture.md`
  * Wrong: `Phase_3_Reference.md`, `Web_UI_Enrichment_Decisions.md`
* **Uniform format:** `Pascal_Snake_Case` without redundant suffixes (no `_Reference` and no `_Register` --- every file here is a reference by definition)
* **The content of each file focuses on:**
  * What is the decision / mechanism?
  * Why was it made? (the justification)
  * How does it work? (the mechanism)
  * Where is it located in the code? (the relevant files)
  * What are the rejected alternatives?
* **Forbidden to include:** phase tables, commit hashes, lists of modified files, execution steps

---

## New Reference Addition Gateway (Pre-Addition Checklist)

Before creating or accepting any new file in `Documentation/References/`, it must pass this pre-check in full. Any item not passed → **reject** (the content is either purified, or converted to `Planning/Backlog/`, or rejected outright):

- [ ] **No commit hashes:** there are no git hashes (such as `9f851c33`) anywhere in the file.
- [ ] **No phase tables:** there are no headings/headers of the type `Phase 1/2/3` or `Wave N` or phase-grouping of decisions.
- [ ] **No deleted source links:** there are no blocks of the type `Sources (all deleted ...)` or references to deleted plan/closeout files as the origin.
- [ ] **No operational file:line audits:** there is no `File.cs:NN` inventory as a tracking tool (locations in the code as an architectural trace are acceptable only if they are short and stable).
- [ ] **No execution dates/branches:** there is no `Created: YYYY-MM-DD`, `Branch: feature/...`, `Phase closed`, `commit XXXXX`.
- [ ] **No Date/Source columns in tables:** decision tables keep only the decision/context/justification/rejected-alternative column.
- [ ] **The name by concept not by task:** the file name describes the decision/mechanism, not the plan or phase name.
- [ ] **Passes the golden rule:** a future developer/agent will need to return to this content to understand a decision or mechanism.

> The goal: this gateway is explicit and amenable to automated inspection in the future to prevent the leakage of execution contamination into durable references.

---

## Documented Boundary Cases

- **`Architecture_Decisions.md` (within `Architecture/Decisions/`):** a record of architectural decisions (ADR-style) — accepted despite being a "record" by nature, because each row describes an original decision (why X instead of Y), not an execution trace. Phase tables and the Date/Source columns are forbidden in it.
- **`License_Server_API.md` (within `Integrations/`):** an external API contract — accepted within Integrations despite being a "contract" not a "mechanism," because it documents the request/response details for a live external service.
- **`Web_UI_Architecture.md` (within `Architecture/`):** overlapping boundaries between display mechanisms and decisions — resolved by describing only the original decisions/mechanisms, and converting any repetition of a complete mechanism into a `↳` reference toward its unified source (such as `Per_Test_Extra_Options_Architecture.md`).
- **Files of `Operations/` (such as `Build_Guide.md`, `DEPLOYMENT_ENVIRONMENT.md`):** an accepted exception from the "procedural prohibitions" — they are guides for established procedures, not decisions, but they are a durable execution reference that is returned to repeatedly, so they deserve their dedicated `Operations/` category. The arbiter: if the content is a "decision/mechanism," it belongs to `Architecture/` or `Integrations/`; and if it is "how do I perform a repeated procedure," it belongs to `Operations/`.

---

## Review Mandatory

As stated in `code-gear1.md` Section 10 (`References folder rules`), exploration and analysis agents must be obliged to read `Documentation/References/` to understand the surrounding environment before proposing any execution method.
